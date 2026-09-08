import { readFileSync } from "node:fs";

const page = readFileSync(new URL("../delivery/index.html", import.meta.url), "utf8");

const requiredTerms = [
  "CHOPPON DELIVERY",
  "SEU NEGÓCIO.",
  "TREINAMENTO",
  "EQUIPAMENTOS",
  "FORNECEDORES",
  "MARKETING",
  "TECNOLOGIA",
  "SUPORTE",
  "+6",
  "FATURAMENTO ILUSTRATIVO",
  "QUERO RECEBER A APRESENTAÇÃO",
  "PERGUNTAS FREQUENTES",
];

for (const term of requiredTerms) {
  if (!page.toUpperCase().includes(term)) throw new Error(`Conteúdo obrigatório ausente: ${term}`);
}
if (!page.includes('action="process-delivery.php"')) throw new Error("O formulário não aponta para o endpoint de delivery.");
if (!page.includes('https://wa.me/5511991748555')) throw new Error("WhatsApp comercial oficial ausente.");
if (!page.includes('family=Barlow+Condensed')) throw new Error("Fonte institucional condensada ausente.");
if (!page.includes('Exemplo meramente ilustrativo')) throw new Error("Aviso obrigatório da simulação ausente.");
if (!page.includes('A ChoppOn não garante faturamento ou rentabilidade')) throw new Error("Aviso de não garantia de faturamento ausente.");
console.log("Landing ChoppOn Delivery validada: conteúdo, CTA oficial, formulário, SEO e aviso de simulação.");
