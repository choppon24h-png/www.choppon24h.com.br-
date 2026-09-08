import { COOKIE_NAME } from "@shared/const";
import { TRPCError } from "@trpc/server";
import { createFranchiseLead, listFranchiseLeads } from "./db";
import { getSessionCookieOptions } from "./_core/cookies";
import { systemRouter } from "./_core/systemRouter";
import { adminProcedure, publicProcedure, router } from "./_core/trpc";
import { franchiseLeadInputSchema } from "../shared/lead";

const attemptsByIdentity = new Map<string, number[]>();
const ATTEMPT_WINDOW_MS = 10 * 60 * 1000;
const MAX_ATTEMPTS_PER_WINDOW = 3;

function maySubmit(identity: string) {
  const now = Date.now();
  const recent = (attemptsByIdentity.get(identity) ?? []).filter(
    timestamp => now - timestamp < ATTEMPT_WINDOW_MS,
  );

  if (recent.length >= MAX_ATTEMPTS_PER_WINDOW) return false;
  recent.push(now);
  attemptsByIdentity.set(identity, recent);
  return true;
}

export const appRouter = router({
    // if you need to use socket.io, read and register route in server/_core/index.ts, all api should start with '/api/' so that the gateway can route correctly
  system: systemRouter,
  auth: router({
    me: publicProcedure.query(opts => opts.ctx.user),
    logout: publicProcedure.mutation(({ ctx }) => {
      const cookieOptions = getSessionCookieOptions(ctx.req);
      ctx.res.clearCookie(COOKIE_NAME, { ...cookieOptions, maxAge: -1 });
      return {
        success: true,
      } as const;
    }),
  }),
  leads: router({
    submit: publicProcedure.input(franchiseLeadInputSchema).mutation(async ({ input }) => {
      if (input.website) {
        throw new TRPCError({ code: "BAD_REQUEST", message: "Não foi possível validar o envio." });
      }

      const identity = `${input.email.trim().toLowerCase()}|${input.phone.replace(/\D/g, "")}`;
      if (!maySubmit(identity)) {
        throw new TRPCError({
          code: "TOO_MANY_REQUESTS",
          message: "Recebemos vários envios. Aguarde alguns minutos antes de tentar novamente.",
        });
      }

      try {
        await createFranchiseLead(input);
        console.info("[Leads] Franchise interest stored successfully");
        return { success: true } as const;
      } catch (error) {
        console.error("[Leads] Failed to store franchise interest", error);
        throw new TRPCError({
          code: "INTERNAL_SERVER_ERROR",
          message: "Não foi possível concluir seu cadastro agora. Tente novamente em instantes.",
        });
      }
    }),
    list: adminProcedure.query(async () => {
      try {
        return await listFranchiseLeads();
      } catch (error) {
        console.error("[Leads] Failed to load protected lead list", error);
        throw new TRPCError({ code: "INTERNAL_SERVER_ERROR", message: "Não foi possível carregar os leads." });
      }
    }),
  }),
});

export type AppRouter = typeof appRouter;
