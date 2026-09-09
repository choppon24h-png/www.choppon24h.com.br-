const puppeteer = require("/home/ubuntu/.local/share/pnpm/store/v11/links/@/puppeteer/25.9.0/13da0e7e9ebf6abb65e32236aaba601a8a3a7848bfc807297b86bf9e5a656165/node_modules/puppeteer");

const origin = "http://127.0.0.1:8013";

async function continueWith(page, model) {
  await page.click(`input[name="modelo"][value="${model}"]`);
  const state = await page.evaluate(() => ({
    disabled: document.getElementById("continueButton").disabled,
    status: document.getElementById("selectionStatus").textContent,
    overflow: document.documentElement.scrollWidth - window.innerWidth > 2,
  }));
  if (state.disabled || !state.status.includes(model === "station" ? "CHOPPON STATION" : "CHOPPON SMART COMPACT") || state.overflow) throw new Error(`Seleção inválida para ${model}: ${JSON.stringify(state)}`);
  await Promise.all([
    page.waitForNavigation({ waitUntil: "networkidle0" }),
    page.click("#continueButton"),
  ]);
  const target = new URL(page.url());
  if (target.pathname !== "/franquia/franquia-form.html" || target.searchParams.get("modelo") !== model || target.searchParams.get("utm_source") !== "instagram") {
    throw new Error(`Destino incorreto para ${model}: ${page.url()}`);
  }
}

(async () => {
  const browser = await puppeteer.launch({ headless: true, executablePath: "/usr/bin/chromium", args: ["--no-sandbox", "--disable-setuid-sandbox"] });
  try {
    const page = await browser.newPage();
    await page.setViewport({ width: 390, height: 844, deviceScaleFactor: 1, isMobile: true });
    await page.goto(`${origin}/instagram/`, { waitUntil: "networkidle0" });
    const initial = await page.evaluate(() => ({
      title: document.title,
      models: document.querySelectorAll('input[name="modelo"]').length,
      disabled: document.getElementById("continueButton").disabled,
      overflow: document.documentElement.scrollWidth - window.innerWidth > 2,
    }));
    if (initial.title !== "Escolha seu modelo | Chopp ON" || initial.models !== 2 || !initial.disabled || initial.overflow) throw new Error(`Estado inicial inválido: ${JSON.stringify(initial)}`);
    await continueWith(page, "station");
    await page.goto(`${origin}/instagram/`, { waitUntil: "networkidle0" });
    await continueWith(page, "smart_compact");
    await page.goto(`${origin}/instagram/?modelo=smart_compact`, { waitUntil: "networkidle0" });
    const preselection = await page.evaluate(() => ({ selected: document.querySelector('input[name="modelo"]:checked')?.value, disabled: document.getElementById("continueButton").disabled }));
    if (preselection.selected !== "smart_compact" || preselection.disabled) throw new Error(`Pré-seleção inválida: ${JSON.stringify(preselection)}`);
    console.log(JSON.stringify({ initial, preselection, routes: ["station", "smart_compact"] }, null, 2));
  } finally { await browser.close(); }
})().catch((error) => { console.error(error); process.exit(1); });
