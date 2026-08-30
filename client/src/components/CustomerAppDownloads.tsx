import { useState } from "react";
import { Apple, Download, Smartphone } from "lucide-react";
import { trackConversion } from "@/lib/tracking";

type Store = "android" | "apple";

function StoreButton({ store, source }: { store: Store; source: string }) {
  const [notice, setNotice] = useState(false);
  const isAndroid = store === "android";
  const Icon = isAndroid ? Smartphone : Apple;
  const label = isAndroid ? "Baixar para Android" : "Baixar para Apple";

  function handleClick() {
    trackConversion("app_download_intent", { platform: store, source });
    setNotice(true);
  }

  return <div className="relative"><button type="button" onClick={handleClick} className={`inline-flex min-w-52 items-center gap-3 rounded-2xl border px-4 py-3 text-left transition active:scale-[.98] ${isAndroid ? "border-[#ff7a00] bg-[#ff7a00] text-[#171717] hover:bg-[#ff922d]" : "border-white/18 bg-[#171717] text-white hover:border-white/45 hover:bg-[#2a2a2a]"}`}><Icon className="size-7" /><span><span className="block text-[10px] font-bold uppercase tracking-[.16em] opacity-70">Em breve</span><span className="block text-sm font-extrabold">{label}</span></span><Download className="ml-auto size-4" /></button>{notice && <p role="status" className="absolute left-0 top-full z-20 mt-2 w-64 rounded-lg bg-white px-3 py-2 text-xs font-bold leading-5 text-[#171717] shadow-xl">O aplicativo Chopp ON será liberado em breve. Vamos avisar quando os downloads estiverem disponíveis.</p>}</div>;
}

export function CustomerAppDownloads({ source }: { source: string }) {
  return <div className="flex flex-wrap gap-3"><StoreButton store="android" source={source} /><StoreButton store="apple" source={source} /></div>;
}
