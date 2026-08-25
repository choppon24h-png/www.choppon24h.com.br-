import { useState } from "react";
import { useLocation } from "wouter";
import { Check, LoaderCircle, ShieldCheck } from "lucide-react";
import { trpc } from "@/lib/trpc";
import { trackConversion } from "@/lib/tracking";

const initial = { fullName: "", email: "", phone: "", city: "", state: "MG", investmentRange: "100k-to-200k" as const, preferredModel: "smart-chopp" as const, message: "", consent: false, website: "" };

const inputClass = "mt-2 w-full rounded-xl border border-white/15 bg-white/[.06] px-4 py-3 text-sm text-white outline-none transition focus:border-[#ff7a00] focus:ring-2 focus:ring-[#ff7a00]/20";

export function FranchiseLeadForm() {
  const [, setLocation] = useLocation();
  const [form, setForm] = useState(initial);
  const [started, setStarted] = useState(false);
  const submit = trpc.leads.submit.useMutation();

  const noteStart = () => {
    if (!started) {
      setStarted(true);
      trackConversion("form_start", { form: "franchise_interest" });
    }
  };

  async function onSubmit(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault();
    if (!form.consent) return;
    await submit.mutateAsync({ ...form, consent: true });
    trackConversion("form_submit_success", { form: "franchise_interest", preferred_model: form.preferredModel });
    setLocation("/franquia/obrigado");
  }

  return (
    <form onSubmit={onSubmit} onFocus={noteStart} className="rounded-[2rem] border border-white/10 bg-[#1a1a1a] p-5 shadow-[0_22px_60px_rgba(0,0,0,.28)] sm:p-7">
      <div className="flex items-start gap-3 border-b border-white/10 pb-5"><span className="grid size-10 place-items-center rounded-xl bg-[#ff7a00] text-[#151515]"><ShieldCheck className="size-5" /></span><div><p className="font-display text-2xl font-bold uppercase">Vamos conversar?</p><p className="mt-1 text-sm text-white/55">Comece com o essencial. O restante é assunto para uma conversa com nosso time.</p></div></div>
      <div className="mt-6 grid gap-4 sm:grid-cols-2">
        <label className="text-sm font-bold sm:col-span-2">Nome completo<input required value={form.fullName} onChange={event => setForm({ ...form, fullName: event.target.value })} className={inputClass} autoComplete="name" /></label>
        <label className="text-sm font-bold">E-mail<input required type="email" value={form.email} onChange={event => setForm({ ...form, email: event.target.value })} className={inputClass} autoComplete="email" /></label>
        <label className="text-sm font-bold">WhatsApp<input required type="tel" value={form.phone} onChange={event => setForm({ ...form, phone: event.target.value })} className={inputClass} autoComplete="tel" placeholder="(00) 00000-0000" /></label>
        <label className="text-sm font-bold">Cidade de interesse<input required value={form.city} onChange={event => setForm({ ...form, city: event.target.value })} className={inputClass} autoComplete="address-level2" /></label>
        <label className="text-sm font-bold">UF<select value={form.state} onChange={event => setForm({ ...form, state: event.target.value })} className={inputClass}><option value="MG">MG</option><option value="SP">SP</option><option value="RJ">RJ</option><option value="ES">ES</option><option value="GO">GO</option><option value="DF">DF</option></select></label>
        <label className="text-sm font-bold">Faixa de investimento<select value={form.investmentRange} onChange={event => setForm({ ...form, investmentRange: event.target.value as typeof form.investmentRange })} className={inputClass}><option value="up-to-50k">Até R$ 50 mil</option><option value="50k-to-100k">R$ 50 mil a R$ 100 mil</option><option value="100k-to-200k">R$ 100 mil a R$ 200 mil</option><option value="above-200k">Acima de R$ 200 mil</option></select></label>
        <label className="text-sm font-bold">Modelo de interesse<select value={form.preferredModel} onChange={event => setForm({ ...form, preferredModel: event.target.value as typeof form.preferredModel })} className={inputClass}><option value="smart-chopp">SMART CHOPP</option><option value="store">Loja Chopp ON</option><option value="not-sure">Quero entender os modelos</option></select></label>
        <label className="text-sm font-bold sm:col-span-2">Conte um pouco do seu momento <span className="font-normal text-white/45">(opcional)</span><textarea value={form.message} onChange={event => setForm({ ...form, message: event.target.value })} className={`${inputClass} min-h-24 resize-y`} maxLength={600} placeholder="Ex.: cidade, ponto comercial ou objetivo de negócio." /></label>
        <label className="sr-only" aria-hidden="true">Website<input tabIndex={-1} autoComplete="off" value={form.website} onChange={event => setForm({ ...form, website: event.target.value })} /></label>
      </div>
      <label className="mt-5 flex items-start gap-3 rounded-xl bg-white/[.035] p-3 text-xs leading-5 text-white/65"><input required type="checkbox" checked={form.consent} onChange={event => setForm({ ...form, consent: event.target.checked })} className="mt-1 size-4 accent-[#ff7a00]" /><span>Autorizo a Chopp ON a usar meus dados exclusivamente para responder ao meu interesse de franquia, conforme a política de privacidade aplicável.</span></label>
      {submit.error && <p role="alert" className="mt-4 rounded-lg bg-red-500/15 px-4 py-3 text-sm text-red-200">{submit.error.message}</p>}
      <button disabled={submit.isPending} className="mt-5 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#ff7a00] px-5 py-4 text-sm font-extrabold text-[#151515] transition hover:bg-[#ff922d] disabled:cursor-not-allowed disabled:opacity-70 active:scale-[.98]">{submit.isPending ? <><LoaderCircle className="size-4 animate-spin" /> Enviando interesse</> : <><Check className="size-4" /> Enviar interesse</>}</button>
    </form>
  );
}
