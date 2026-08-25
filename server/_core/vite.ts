import express, { type Express, type NextFunction, type Request, type Response } from "express";
import fs from "fs";
import { type Server } from "http";
import { nanoid } from "nanoid";
import path from "path";
import { createServer as createViteServer } from "vite";
import viteConfig from "../../vite.config";
import superjson from "superjson";
import type { HeadMeta } from "../../client/src/ssr/prefetch";

const canonicalOrigin = (process.env.CANONICAL_ORIGIN || "https://www.choppon24h.com.br").replace(/\/$/, "");
const siteName = process.env.SITE_NAME || "Chopp ON";

function escapeHtml(value: string) {
  return value.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;");
}

function headTags(head: HeadMeta) {
  const title = escapeHtml(head.title);
  const description = escapeHtml(head.description);
  const canonical = head.canonicalPath ? `${canonicalOrigin}${head.canonicalPath}` : "";
  const image = head.ogImage ? (head.ogImage.startsWith("/") ? `${canonicalOrigin}${head.ogImage}` : head.ogImage) : "";
  const tags = [
    `<title>${title}</title>`,
    `<meta name="description" content="${description}" />`,
    `<meta property="og:type" content="website" />`,
    `<meta property="og:title" content="${title}" />`,
    `<meta property="og:description" content="${description}" />`,
    `<meta property="og:site_name" content="${escapeHtml(siteName)}" />`,
    `<meta property="og:locale" content="pt_BR" />`,
    `<meta name="twitter:card" content="${image ? "summary_large_image" : "summary"}" />`,
    `<meta name="twitter:title" content="${title}" />`,
    `<meta name="twitter:description" content="${description}" />`,
  ];
  if (canonical) tags.push(`<link rel="canonical" href="${escapeHtml(canonical)}" />`, `<meta property="og:url" content="${escapeHtml(canonical)}" />`);
  if (image) tags.push(`<meta property="og:image" content="${escapeHtml(image)}" />`, `<meta property="og:image:alt" content="${escapeHtml(head.ogImageAlt || "Chopp ON")}" />`, `<meta name="twitter:image" content="${escapeHtml(image)}" />`);
  if (head.noindex || head.notFound) tags.push(`<meta name="robots" content="noindex, follow" />`);
  return tags.join("\n");
}

function composeHtml(template: string, appHtml: string, head: HeadMeta, dehydratedState: unknown) {
  const serialized = JSON.stringify(superjson.serialize(dehydratedState)).replace(/</g, "\\u003c");
  return template
    .replace("</body>", () => `<script>window.__RQ_STATE__ = ${serialized}</script></body>`)
    .replace("<!--app-head-->", () => headTags(head))
    .replace("<!--app-html-->", () => appHtml);
}

const blockedPaths = [
  "/admin/get-franchise-data.php",
  "/admin/painel-franquia.html",
  "/franchise_data.json",
  "/process-franchise.php",
  "/caixa",
  "/apk",
  "/monitor chopp on",
  "/readme.md",
  "/alteracoes_realizadas.txt",
];

function applyPublicUrlPolicy(req: Request, res: Response, next: NextFunction) {
  const normalizedPath = req.path.toLowerCase();
  if (blockedPaths.some(path => normalizedPath === path || normalizedPath.startsWith(`${path}/`))) {
    res.status(404).set("X-Robots-Tag", "noindex, nofollow").end();
    return;
  }
  if (normalizedPath === "/indexold.html" || normalizedPath === "/index.html") {
    res.redirect(301, "/");
    return;
  }
  if (normalizedPath === "/chopp" || normalizedPath === "/chopp/") {
    res.redirect(301, "/delivery");
    return;
  }
  next();
}

export async function setupVite(app: Express, server: Server) {
  const serverOptions = {
    middlewareMode: true,
    hmr: { server },
    allowedHosts: true as const,
  };

  const vite = await createViteServer({
    ...viteConfig,
    configFile: false,
    server: serverOptions,
    appType: "custom",
  });

  app.use(applyPublicUrlPolicy);
  app.use(vite.middlewares);
  app.use("*", async (req, res, next) => {
    const url = req.originalUrl;

    try {
      const clientTemplate = path.resolve(
        import.meta.dirname,
        "../..",
        "client",
        "index.html"
      );

      // always reload the index.html file from disk incase it changes
      let template = await fs.promises.readFile(clientTemplate, "utf-8");
      template = template.replace(`src="/src/entry-client.tsx"`, `src="/src/entry-client.tsx?v=${nanoid()}"`);
      template = await vite.transformIndexHtml(url, template);
      template = template.replace("</head>", `<link rel="stylesheet" href="/src/index.css?direct" data-ssr-dev-css></head>`);
      const { render } = await vite.ssrLoadModule("/src/entry-server.tsx");
      const { html, dehydratedState, head } = await render(url);
      res.status(head.notFound ? 404 : 200).set({ "Content-Type": "text/html", "Cache-Control": "no-cache" }).end(composeHtml(template, html, head, dehydratedState));
    } catch (e) {
      vite.ssrFixStacktrace(e as Error);
      next(e);
    }
  });
}

export function serveStatic(app: Express) {
  const distPath =
    process.env.NODE_ENV === "development"
      ? path.resolve(import.meta.dirname, "../..", "dist", "public")
      : path.resolve(import.meta.dirname, "public");
  if (!fs.existsSync(distPath)) {
    console.error(
      `Could not find the build directory: ${distPath}, make sure to build the client first`
    );
  }

  app.use(applyPublicUrlPolicy);
  app.use((req, res, next) => {
    if (req.path !== "/" && /\/+$/ .test(req.path)) {
      const query = req.originalUrl.slice(req.path.length);
      return res.redirect(301, req.path.replace(/\/+$/, "") + query);
    }
    next();
  });

  app.use(express.static(distPath, { index: false, redirect: false }));
  app.use("*", async (req, res) => {
    const template = await fs.promises.readFile(path.resolve(distPath, "index.html"), "utf-8");
    try {
      const entryPath = path.resolve(import.meta.dirname, "server-ssr", "entry-server.js");
      const { render } = await import(entryPath);
      const { html, dehydratedState, head } = await render(req.originalUrl);
      res.status(head.notFound ? 404 : 200).set("Cache-Control", "no-cache").type("html").end(composeHtml(template, html, head, dehydratedState));
    } catch (error) {
      console.error("[SSR] render failed, serving client shell", error);
      const fallback: HeadMeta = { title: siteName, description: "Chopp ON — sua melhor experiência em chopp." };
      res.status(200).set("Cache-Control", "no-cache").type("html").end(composeHtml(template, "", fallback, {}));
    }
  });
}
