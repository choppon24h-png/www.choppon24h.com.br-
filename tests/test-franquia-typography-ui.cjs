const puppeteer = require("/home/ubuntu/.local/share/pnpm/store/v11/links/@/puppeteer/25.9.0/13da0e7e9ebf6abb65e32236aaba601a8a3a7848bfc807297b86bf9e5a656165/node_modules/puppeteer");

const url = "http://127.0.0.1:8011/franquia/";

async function inspect(name, viewport) {
  const browser = await puppeteer.launch({
    headless: true,
    executablePath: "/usr/bin/chromium",
    args: ["--no-sandbox", "--disable-setuid-sandbox"],
  });
  try {
    const page = await browser.newPage();
    await page.setViewport(viewport);
    await page.goto(url, { waitUntil: "networkidle0" });
    const result = await page.evaluate(() => {
      const heading = document.querySelector(".hero h1");
      const style = getComputedStyle(heading);
      const rect = heading.getBoundingClientRect();
      return {
        font: style.fontFamily,
        weight: style.fontWeight,
        loaded: document.fonts.check('800 48px "Barlow Condensed"'),
        overflow: (heading.scrollWidth - heading.clientWidth) > 2,
        width: Math.round(rect.width),
        height: Math.round(rect.height),
      };
    });
    if (!result.font.includes("Barlow Condensed")) throw new Error(`${name}: a fonte de destaque não foi aplicada.`);
    if (result.weight !== "800") throw new Error(`${name}: o peso tipográfico esperado é 800, recebido ${result.weight}.`);
    if (!result.loaded) throw new Error(`${name}: a fonte de destaque não foi carregada.`);
    if (result.overflow) throw new Error(`${name}: o título principal ultrapassou seu contêiner.`);
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
