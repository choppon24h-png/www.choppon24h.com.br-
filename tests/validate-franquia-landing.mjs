import { readFileSync } from "node:fs";

const landing = readFileSync(new URL("../franquia/index.html", import.meta.url), "utf8");
const form = readFileSync(new URL("../franquia/franquia-form.html", import.meta.url), "utf8");

const requiredLandingTerms = [
  "MODELOS CHOPPON",
  "CHOPPON SMART",
  "CHOPPON SMART COMPACT",
  "CHOPPON STATION",
  "CHOPPON DELIVERY",
  "QUATRO FORMAS DE",
];

const requiredCtas = [
  "franquia-form.html?modelo=smart",
  "franquia-form.html?modelo=smart_compact",
  "franquia-form.html?modelo=station",
  "franquia-form.html?modelo=delivery",
];

for (const term of requiredLandingTerms) {
  if (!landing.toUpperCase().includes(term)) throw new Error(`Conteúdo esperado ausente da landing: ${term}`);
}
for (const cta of requiredCtas) {
  if (!landing.includes(cta)) throw new Error(`CTA esperado ausente da landing: ${cta}`);
}
if (!landing.includes("https://wa.me/5511991748555")) throw new Error("CTA comercial oficial ausente da landing.");
if (/R\$\s*\d/.test(landing)) throw new Error("A landing comercial não deve conter valores de investimento.");
if (!landing.includes('rel="canonical" href="https://www.choppon24h.com.br/franquia/"')) throw new Error("Canonical da landing ausente.");
if (!landing.includes('family=Barlow+Condensed')) throw new Error("Fonte condensada legível ausente da landing.");
if (!landing.includes('--display:"Barlow Condensed"')) throw new Error("Família tipográfica de destaque não foi aplicada.");
if (!form.includes("new URLSearchParams(window.location.search).get('modelo')")) throw new Error("Formulário não reconhece CTA de modelo.");
console.log("Landing de franquias validada: modelos, CTAs, canonical e ausência de valores financeiros.");
