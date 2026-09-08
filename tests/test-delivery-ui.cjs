const fs = require("node:fs");
const path = require("node:path");
const puppeteer = require("/home/ubuntu/.local/share/pnpm/store/v11/links/@/puppeteer/25.9.0/13da0e7e9ebf6abb65e32236aaba601a8a3a7848bfc807297b86bf9e5a656165/node_modules/puppeteer");

const url = "http://127.0.0.1:8011/delivery/";
const outputDir = path.join(__dirname, "artifacts");
fs.mkdirSync(outputDir, { recursive: true });

async function inspect(name, viewport) {
  const browser = await puppeteer.launch({ headless: true, executablePath: "/usr/bin/chromium", args: ["--no-sandbox", "--disable-setuid-sandbox"] });
  try {
    const page = await browser.newPage();
    await page.setViewport(viewport);
    await page.goto(url, { waitUntil: "networkidle0" });
    await page.screenshot({ path: path.join(outputDir, `delivery-${name}.png`), fullPage: true });
    const before = await page.$eval("#revenue-value", (node) => node.textContent.trim());
    await page.$eval("#events", (node) => { node.value = "12"; node.dispatchEvent(new Event("input", { bubbles: true })); });
    const result = await page.evaluate(() => ({
      heading: document.querySelector(".hero h1")?.textContent.replace(/\s+/g, " ").trim(),
      font: getComputedStyle(document.querySelector(".hero h1")).fontFamily,
      revenue: document.querySelector("#revenue-value")?.textContent.trim(),
      detail: document.querySelector("#revenue-detail")?.textContent.trim(),
      cards: document.querySelectorAll(".support-card").length,
      faqs: document.querySelectorAll("details").length,
      formAction: document.querySelector("#delivery-form")?.getAttribute("action"),
      buttonsVisible: [...document.querySelectorAll(".button")].filter((button) => { const rect = button.getBoundingClientRect(); return rect.width > 0 && rect.height > 0; }).length,
      heroOverflow: (() => { const heading = document.querySelector(".hero h1"); return (heading.scrollWidth - heading.clientWidth) > 2; })(),
    }));
    if (!result.heading.includes("Seu negócio")) throw new Error(`${name}: título principal ausente.`);
    if (!result.font.includes("Barlow Condensed")) throw new Error(`${name}: fonte institucional não aplicada.`);
    if (result.revenue === before || !result.revenue.includes("18.000")) throw new Error(`${name}: simulador não respondeu à alteração de eventos.`);
    if (result.detail !== "12 eventos × R$ 1.500") throw new Error(`${name}: detalhe do simulador inesperado: ${result.detail}`);
    if (result.cards !== 8 || result.faqs < 9 || result.formAction !== "process-delivery.php" || result.buttonsVisible < 6 || result.heroOverflow) throw new Error(`${name}: estrutura visual ou interativa inválida: ${JSON.stringify(result)}`);
    return { name, ...result };
  } finally { await browser.close(); }
}

(async () => {
  const desktop = await inspect("desktop", { width: 1440, height: 1000, deviceScaleFactor: 1 });
  const mobile = await inspect("mobile", { width: 390, height: 844, deviceScaleFactor: 1, isMobile: true });
  console.log(JSON.stringify({ desktop, mobile }, null, 2));
})().catch((error) => { console.error(error); process.exit(1); });
