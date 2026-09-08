const puppeteer = require("/home/ubuntu/.local/share/pnpm/store/v11/links/@/puppeteer/25.9.0/13da0e7e9ebf6abb65e32236aaba601a8a3a7848bfc807297b86bf9e5a656165/node_modules/puppeteer");

const url = "http://127.0.0.1:8012/#marca";

async function inspect(name, viewport) {
  const browser = await puppeteer.launch({ headless: true, executablePath: "/usr/bin/chromium", args: ["--no-sandbox", "--disable-setuid-sandbox"] });
  try {
    const page = await browser.newPage();
    await page.setViewport(viewport);
    await page.goto(url, { waitUntil: "networkidle0" });
    const result = await page.evaluate(() => {
      const root = document.querySelector("#marca");
      const hero = root?.querySelector("h2");
      const links = [...root?.querySelectorAll("a") ?? []].map((link) => ({ text: link.textContent?.replace(/\s+/g, " ").trim(), href: link.getAttribute("href") }));
      const pageText = document.body.textContent ?? "";
      return {
        hero: hero?.textContent?.replace(/\s+/g, " ").trim(),
        heroFont: hero ? getComputedStyle(hero).fontFamily : "",
        fontLoaded: document.fonts.check('800 48px "Barlow Condensed"'),
        links,
        modelCount: ["CHOPPON SMART", "CHOPPON SMART COMPACT", "CHOPPON STATION", "CHOPPON DELIVERY"].filter((model) => pageText.includes(model)).length,
        processCopy: pageText.includes("Pedido de registro de marca no INPI") && pageText.includes("Processo de registro em andamento."),
        horizontalOverflow: document.documentElement.scrollWidth - window.innerWidth > 2,
      };
    });
    if (!result.hero?.toUpperCase().includes("NÃO É APENAS CHOPP.")) throw new Error(`${name}: título institucional ausente.`);
    if (!result.heroFont.includes("Barlow Condensed")) throw new Error(`${name}: tipografia institucional não foi aplicada pelo CSS.`);
    if (!result.links.some((link) => link.href === "/franquia#cadastro") || !result.links.some((link) => link.href === "/franquia")) throw new Error(`${name}: CTAs de franquia ausentes.`);
    if (result.modelCount !== 4 || !result.processCopy || result.horizontalOverflow) throw new Error(`${name}: estrutura ou responsividade inválida: ${JSON.stringify(result)}`);
    return { name, ...result };
  } finally {
    await browser.close();
  }
}

(async () => {
  const desktop = await inspect("desktop", { width: 1440, height: 1000, deviceScaleFactor: 1 });
  const mobile = await inspect("mobile", { width: 390, height: 844, deviceScaleFactor: 1, isMobile: true });
  console.log(JSON.stringify({ desktop, mobile }, null, 2));
})().catch((error) => { console.error(error); process.exit(1); });
