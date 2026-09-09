const puppeteer = require("/home/ubuntu/.local/share/pnpm/store/v11/links/@/puppeteer/25.9.0/13da0e7e9ebf6abb65e32236aaba601a8a3a7848bfc807297b86bf9e5a656165/node_modules/puppeteer");

const url = "http://127.0.0.1:8013/#marca";

async function inspect(name, viewport) {
  const browser = await puppeteer.launch({ headless: true, executablePath: "/usr/bin/chromium", args: ["--no-sandbox", "--disable-setuid-sandbox"] });
  try {
    const page = await browser.newPage();
    await page.setViewport(viewport);
    await page.goto(url, { waitUntil: "networkidle0" });
    const result = await page.evaluate(() => {
      const brand = document.querySelector("#marca");
      const heading = brand?.querySelector("h2");
      const links = [...brand?.querySelectorAll("a") ?? []].map((link) => link.getAttribute("href"));
      const headerCta = [...document.querySelectorAll("header a")].find((link) => link.textContent?.includes("Quero empreender"));
      const body = document.body.textContent ?? "";
      return {
        heading: heading?.textContent?.replace(/\s+/g, " ").trim(),
        font: heading ? getComputedStyle(heading).fontFamily : "",
        links,
        headerCta: headerCta?.getAttribute("href"),
        fourModels: ["CHOPPON SMART", "CHOPPON SMART COMPACT", "CHOPPON STATION", "CHOPPON DELIVERY"].every((model) => body.includes(model)),
        processSafe: body.includes("Pedido de registro de marca no INPI") && body.includes("Processo de registro em andamento."),
        horizontalOverflow: document.documentElement.scrollWidth - window.innerWidth > 2,
      };
    });
    if (!result.heading?.toUpperCase().includes("NÃO É APENAS CHOPP.")) throw new Error(`${name}: título A Marca ausente.`);
    if (!result.font.includes("Barlow Condensed")) throw new Error(`${name}: família tipográfica institucional ausente.`);
    if (!result.links.includes("/franquia#cadastro") || !result.links.includes("/franquia")) throw new Error(`${name}: CTAs de franquia ausentes.`);
    if (result.headerCta !== "/franquia/franquia-form.html") throw new Error(`${name}: CTA Quero empreender aponta para ${result.headerCta}.`);
    if (!result.fourModels || !result.processSafe || result.horizontalOverflow) throw new Error(`${name}: home estática inválida: ${JSON.stringify(result)}`);
    return { name, ...result };
  } finally { await browser.close(); }
}

(async () => {
  const desktop = await inspect("desktop", { width: 1440, height: 1000, deviceScaleFactor: 1 });
  const mobile = await inspect("mobile", { width: 390, height: 844, deviceScaleFactor: 1, isMobile: true });
  console.log(JSON.stringify({ desktop, mobile }, null, 2));
})().catch((error) => { console.error(error); process.exit(1); });
