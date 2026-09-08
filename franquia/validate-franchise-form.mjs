import fs from "node:fs";
import path from "node:path";
import { fileURLToPath } from "node:url";

const directory = path.dirname(fileURLToPath(import.meta.url));
const file = path.join(directory, "franquia-form.html");
const html = fs.readFileSync(file, "utf8");

const requiredFragments = [
  'action="process-franchise.php"',
  'id="cpf"',
  'id="aceite_lgpd"',
  'data-step="0"',
  'data-step="4"',
  'Chopp ON Station',
  'A partir de R$ 120 mil',
  'ChoppOn Smart',
  'R$ 100 mil',
  'ChoppOn Smart Compact',
  'A partir de R$ 18 mil',
  'ChoppOn Delivery',
  'R$ 35.000,00',
  'function validCpf',
  'function buildMapping',
  'function validatePanel',
  "list.replaceChildren",
  'for (let index = 0; index < panels.length - 1; index += 1)',
];

for (const fragment of requiredFragments) {
  if (!html.includes(fragment)) throw new Error(`Conteúdo obrigatório ausente: ${fragment}`);
}

if (html.includes('resultList").innerHTML') || html.includes("resultList').innerHTML")) {
  throw new Error("O diagnóstico não deve interpolar dados do candidato com innerHTML.");
}

const scriptContent = html.match(/<script>\s*([\s\S]*?)\s*<\/script>/)?.[1];
if (!scriptContent) throw new Error("Script do formulário não encontrado.");
new Function(scriptContent);

console.log("Formulário de qualificação validado com sucesso.");
