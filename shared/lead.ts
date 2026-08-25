import { z } from "zod";

export const investmentRanges = [
  "up-to-50k",
  "50k-to-100k",
  "100k-to-200k",
  "above-200k",
] as const;

export const preferredModels = ["smart-chopp", "store", "not-sure"] as const;

export const franchiseLeadInputSchema = z.object({
  fullName: z.string().trim().min(3, "Informe seu nome completo.").max(120),
  email: z.string().trim().email("Informe um e-mail válido.").max(320),
  phone: z.string().trim().min(10, "Informe um WhatsApp válido.").max(24),
  city: z.string().trim().min(2, "Informe a cidade de interesse.").max(120),
  state: z.string().regex(/^[A-Z]{2}$/, "Selecione uma UF válida."),
  investmentRange: z.enum(investmentRanges),
  preferredModel: z.enum(preferredModels),
  message: z.string().trim().max(600).optional(),
  consent: z.literal(true),
  website: z.string().max(0).optional(),
});

export type FranchiseLeadInput = z.infer<typeof franchiseLeadInputSchema>;
