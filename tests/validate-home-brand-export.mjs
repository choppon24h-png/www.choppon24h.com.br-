import { existsSync, readFileSync } from "node:fs";

const root = new URL("../", import.meta.url);
const page = readFileSync(new URL("../index.html", import.meta.url), "utf8");

for (const term of [
  'id="marca"',
  "Não é apenas chopp.",
  "É uma marca para",
  "CHOPPON SMART",
  "CHOPPON SMART COMPACT",
  "CHOPPON STATION",
  "CHOPPON DELIVERY",
  "Pedido de registro de marca no INPI",
  "Processo nº 937670413",
  "Processo de registro em andamento.",
  "Serra do Cipó",
  "Sete Lagoas",
]) {
  if (!page.includes(term)) throw new Error(`Conteúdo institucional ausente: ${term}`);
}

for (const asset of ["assets/index-CxEscHZ2.js", "assets/index-DNl-VaVP.css", "assets/vendor-icons-C4PosQkb.js"]) {
  if (!page.includes(`/${asset}`)) throw new Error(`Asset não referenciado pela home: ${asset}`);
  if (!existsSync(new URL(`../${asset}`, import.meta.url))) throw new Error(`Asset ausente da publicação estática: ${asset}`);
}

if (page.includes("Marca registrada no INPI") || page.includes("Marca patenteada")) throw new Error("A home contém alegação jurídica não confirmada.");
console.log("Home estática validada: seção A Marca, assets, CTAs e linguagem jurídica segura.");
