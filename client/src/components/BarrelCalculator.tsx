import { useMemo, useState } from "react";
import { Calculator, Minus, Plus } from "lucide-react";
import { WhatsAppLink } from "./marketing";

export function BarrelCalculator() {
  const [guests, setGuests] = useState(35);
  const [occasion, setOccasion] = useState("encontro");
  const result = useMemo(() => {
    const factor = occasion === "evento" ? 0.65 : occasion === "celebracao" ? 0.7 : 0.55;
    const liters = Math.max(30, Math.ceil((guests * factor) / 5) * 5);
    return { liters, barrels: liters <= 30 ? "1 barril de 30L" : liters <= 50 ? "1 barril de 50L" : `${Math.ceil(liters / 50)} barris de 50L` };
  }, [guests, occasion]);

  return <section className="rounded-[2rem] border border-[#ff7a00]/25 bg-[#171717] p-5 shadow-[0_20px_55px_rgba(0,0,0,.22)] sm:p-8"><div className="flex gap-3"><span className="grid size-11 place-items-center rounded-xl bg-[#ff7a00] text-[#171717]"><Calculator className="size-5" /></span><div><p className="font-display text-2xl font-bold uppercase">Calculadora de barris</p><p className="text-sm text-white/55">Uma referência para iniciar seu planejamento.</p></div></div><div className="mt-7 grid gap-5 md:grid-cols-2"><div><p className="text-sm font-bold">Quantidade de pessoas</p><div className="mt-3 flex items-center justify-between rounded-xl border border-white/10 bg-white/[.04] p-2"><button type="button" onClick={() => setGuests(value => Math.max(10, value - 5))} className="grid size-10 place-items-center rounded-lg bg-white/10 hover:bg-white/15" aria-label="Diminuir convidados"><Minus className="size-4" /></button><output className="font-display text-4xl font-black text-[#ff8b27]">{guests}</output><button type="button" onClick={() => setGuests(value => Math.min(500, value + 5))} className="grid size-10 place-items-center rounded-lg bg-white/10 hover:bg-white/15" aria-label="Aumentar convidados"><Plus className="size-4" /></button></div></div><label className="text-sm font-bold">Tipo de encontro<select value={occasion} onChange={event => setOccasion(event.target.value)} className="mt-3 w-full rounded-xl border border-white/10 bg-white/[.04] px-4 py-3 text-sm text-white outline-none focus:border-[#ff7a00]"><option value="encontro">Encontro entre amigos</option><option value="celebracao">Celebração</option><option value="evento">Evento de longa duração</option></select></label></div><div className="mt-6 rounded-2xl bg-[#ff7a00] p-5 text-[#171717]"><p className="text-xs font-extrabold uppercase tracking-[.16em]">Referência estimada</p><p className="mt-2 font-display text-5xl font-black">{result.liters}L</p><p className="mt-1 text-sm font-bold">{result.barrels}</p><p className="mt-4 text-xs leading-5 text-[#171717]/75">A indicação pode variar com duração, clima e perfil dos convidados. Confirme a melhor combinação no atendimento.</p></div><div className="mt-5"><WhatsAppLink source="delivery_calculator" label="Confirmar no WhatsApp" /></div></section>;
}
