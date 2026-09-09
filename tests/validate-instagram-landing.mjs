import { readFileSync } from "node:fs";

const page = readFileSync(new URL("../instagram/index.html", import.meta.url), "utf8");

for (const required of [
  "CHOPPON STATION",
  "CHOPPON SMART COMPACT",
  'value="station"',
  'value="smart_compact"',
  "franquia-form.html?modelo=",
  "utm_source=instagram",
  "cadastro de interesse em franquia",
]) {
  if (!page.includes(required)) throw new Error(`Conteúdo obrigatório ausente: ${required}`);
}

if (page.includes("aprovação de franquia garantida") || page.includes("faturamento garantido")) throw new Error("A landing contém promessa comercial indevida.");
console.log("Landing de Instagram validada: modelos, encaminhamento e linguagem comercial segura.");
