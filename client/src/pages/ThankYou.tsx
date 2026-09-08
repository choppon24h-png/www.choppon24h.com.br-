import { ArrowRight, CheckCircle2 } from "lucide-react";
import { Link } from "wouter";
import { PageShell, WhatsAppLink } from "@/components/marketing";

export default function ThankYou() {
  return <PageShell><main className="grid min-h-[64vh] place-items-center bg-[#121212] px-4 py-16"><section className="max-w-xl rounded-[2rem] border border-white/10 bg-white/[.04] p-8 text-center shadow-[0_22px_60px_rgba(0,0,0,.28)] sm:p-12"><CheckCircle2 className="mx-auto size-12 text-[#ff7a00]" /><p className="mt-6 font-display text-5xl font-black uppercase leading-[.88]">Recebemos seu interesse.</p><p className="mt-5 text-sm leading-7 text-white/65">O cadastro foi enviado ao time Chopp ON. Em breve, vocês poderão conversar sobre a sua cidade e o modelo que mais combina com seu plano.</p><div className="mt-8 flex flex-wrap justify-center gap-3"><WhatsAppLink source="franchise_thank_you" label="Antecipar conversa no WhatsApp" /><Link href="/franquia" className="inline-flex items-center gap-2 rounded-full border border-white/20 px-5 py-3.5 text-sm font-extrabold hover:border-[#ff7a00] hover:text-[#ff8b27]">Voltar para franquias <ArrowRight className="size-4" /></Link></div></section></main></PageShell>;
}
