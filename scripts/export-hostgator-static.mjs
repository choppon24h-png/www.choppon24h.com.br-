import fs from "node:fs/promises";
import path from "node:path";
import { fileURLToPath } from "node:url";
import superjson from "superjson";

const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), "..");
const output = path.join(root, "release", "hostgator-static");
const clientBuild = path.join(root, "dist", "public");
const logoSource = "/home/ubuntu/webdev-static-assets/chopp-on-logo.png";
const origin = "https://www.choppon24h.com.br";
const routes = ["/", "/franquia", "/franquia/obrigado", "/delivery", "/aplicativo", "/franqueado"];

function escapeHtml(value) {
  return value.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#39;");
}

function headTags(head) {
  const title = escapeHtml(head.title);
  const description = escapeHtml(head.description);
  const canonical = head.canonicalPath ? `${origin}${head.canonicalPath}` : "";
  const image = head.ogImage ? `${origin}/assets/chopp-on-logo.png` : "";
  const tags = [
    `<title>${title}</title>`,
    `<meta name="description" content="${description}" />`,
    `<meta property="og:type" content="website" />`,
    `<meta property="og:title" content="${title}" />`,
    `<meta property="og:description" content="${description}" />`,
    '<meta property="og:site_name" content="Chopp ON" />',
    '<meta property="og:locale" content="pt_BR" />',
    `<meta name="twitter:card" content="${image ? "summary_large_image" : "summary"}" />`,
    `<meta name="twitter:title" content="${title}" />`,
    `<meta name="twitter:description" content="${description}" />`,
  ];
  if (canonical) tags.push(`<link rel="canonical" href="${escapeHtml(canonical)}" />`, `<meta property="og:url" content="${escapeHtml(canonical)}" />`);
  if (image) tags.push(`<meta property="og:image" content="${image}" />`, `<meta property="og:image:alt" content="${escapeHtml(head.ogImageAlt || "Logo Chopp ON")}" />`, `<meta name="twitter:image" content="${image}" />`);
  if (head.noindex || head.notFound) tags.push('<meta name="robots" content="noindex, follow" />');
  return tags.join("\n");
}

function composeHtml(template, appHtml, head, state) {
  const serialized = JSON.stringify(superjson.serialize(state)).replace(/</g, "\\u003c");
  return template
    .replace(/\s*<script\s+defer\s+src="%VITE_ANALYTICS_ENDPOINT%\/umami"[\s\S]*?<\/script>/, "")
    .replace("<!--app-head-->", headTags(head))
    .replace("<!--app-html-->", appHtml)
    .replace("</body>", `<script>window.__RQ_STATE__ = ${serialized}</script></body>`);
}

const htaccess = `Options -Indexes
DirectoryIndex index.html

<IfModule mod_rewrite.c>
  RewriteEngine On
  RewriteRule ^index\\.html$ / [R=301,L]
  RewriteRule ^chopp/?$ /delivery/ [R=301,L]
  RewriteRule ^chopp/(.*)$ /delivery/ [R=301,L]
  RewriteRule ^(?:admin|client|server|drizzle|shared|docs|patches)(?:/|$) - [F,L]
  RewriteRule ^(?:package\\.json|pnpm-lock\\.yaml|vite\\.config(?:\\.ssr)?\\.ts|todo\\.md|README\\.md)$ - [F,L]
</IfModule>
`;

await fs.rm(output, { recursive: true, force: true });
await fs.mkdir(output, { recursive: true });
await fs.cp(clientBuild, output, { recursive: true });
await fs.rm(path.join(output, "__manus__"), { recursive: true, force: true });
await fs.rm(path.join(output, ".gitkeep"), { force: true });
await fs.mkdir(path.join(output, "assets"), { recursive: true });
await fs.copyFile(logoSource, path.join(output, "assets", "chopp-on-logo.png"));
await fs.writeFile(path.join(output, ".htaccess"), htaccess, "utf8");

const template = await fs.readFile(path.join(output, "index.html"), "utf8");
const { render } = await import(path.join(root, "dist", "server-ssr", "entry-server.js"));

for (const route of routes) {
  const { html, dehydratedState, head } = await render(route);
  const destination = route === "/" ? path.join(output, "index.html") : path.join(output, route.slice(1), "index.html");
  await fs.mkdir(path.dirname(destination), { recursive: true });
  await fs.writeFile(destination, composeHtml(template, html, head, dehydratedState), "utf8");
}

const sitemap = `<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>${origin}/</loc></url>
  <url><loc>${origin}/franquia/</loc></url>
  <url><loc>${origin}/delivery/</loc></url>
  <url><loc>${origin}/aplicativo/</loc></url>
  <url><loc>${origin}/franqueado/</loc></url>
</urlset>
`;
await fs.writeFile(path.join(output, "sitemap.xml"), sitemap, "utf8");
await fs.writeFile(path.join(output, "robots.txt"), `User-agent: *\nAllow: /\nSitemap: ${origin}/sitemap.xml\n`, "utf8");
await fs.writeFile(path.join(output, "README-HOSTGATOR.txt"), "Este diretório é o único conteúdo que deve ser enviado ao Document Root real de choppon24h.com.br. Envie index.html, assets/, franquia/, delivery/, aplicativo/, franqueado/, robots.txt, sitemap.xml e .htaccess. Não envie client/, server/, drizzle/, shared/, package.json ou pnpm-lock.yaml.\n", "utf8");
console.log(`Pacote estático criado em: ${output}`);
