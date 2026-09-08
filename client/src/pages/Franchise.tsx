import {
  ArrowRight,
  Building2,
  CheckCircle2,
  CircleDollarSign,
  Clock3,
  CreditCard,
  Gauge,
  MapPinned,
  MonitorCog,
  ShieldCheck,
  Sparkles,
  Store,
  Truck,
  Wifi,
} from "lucide-react";
import { FranchiseLeadForm } from "@/components/FranchiseLeadForm";
import { Eyebrow, JsonLd, PageShell, WhatsAppLink } from "@/components/marketing";
import { franchiseModelComparison, franchiseModels } from "@/data/franchiseModels";

const stages = [
  ["01", "Conversa de cenário", "Entendemos perfil, cidade e momento de negócio antes de indicar um caminho."],
  ["02", "Modelo e ponto", "Alinhamos o formato mais adequado e os critérios para avaliar a oportunidade."],
  ["03", "Implantação", "Planejamento da estrutura, identidade, treinamento e preparação da operação."],
  ["04", "Abertura e suporte", "Acompanhamento da entrada em operação e conexão com a rede Chopp ON."],
] as const;

const modelIcons = {
  smart: MonitorCog,
  compact: Building2,
  station: Store,
  delivery: Truck,
} as const;

const modelVisuals = {
  smart: { icon: Wifi, panel: "bg-[#171717] text-white", number: "text-white/[.08]", surface: "border-white/10 bg-white/[.045]", chip: "border-white/12 bg-white/[.05] text-white/76", cta: "text-[#ff9d4d]" },
  compact: { icon: Gauge, panel: "bg-white text-[#171717]", number: "text-black/[.06]", surface: "border-black/8 bg-[#f7f2eb]", chip: "border-black/10 bg-white text-black/65", cta: "text-[#d85d00]" },
  station: { icon: CreditCard, panel: "bg-[#23170d] text-white", number: "text-white/[.08]", surface: "border-white/10 bg-white/[.045]", chip: "border-white/12 bg-white/[.05] text-white/76", cta: "text-[#ffad63]" },
  delivery: { icon: Truck, panel: "bg-[#ff7a00] text-[#19120d]", number: "text-black/[.08]", surface: "border-black/10 bg-[#ff8d2d]", chip: "border-black/12 bg-white/25 text-black/70", cta: "text-[#18110b]" },
} as const;

export default function Franchise() {
  return (
    <PageShell>
      <JsonLd data={{ "@context": "https://schema.org", "@type": "Organization", name: "Chopp ON", url: "https://www.choppon24h.com.br/franquia", logo: "https://www.choppon24h.com.br/manus-storage/chopp-on-logo_ee1c339e.png", sameAs: ["https://www.instagram.com/choppon24h.oficial"] }} />
      <main>
        <section className="relative isolate overflow-hidden border-b border-white/10 bg-[#121212] py-16 sm:py-24">
          <div className="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_82%_18%,rgba(255,122,0,.32),transparent_24%),radial-gradient(circle_at_10%_80%,rgba(255,122,0,.12),transparent_25%)]" />
          <div className="container grid items-center gap-12 lg:grid-cols-[1.05fr_.95fr]">
            <div>
              <Eyebrow>Franquias Chopp ON</Eyebrow>
              <h1 className="max-w-3xl font-display text-5xl font-black uppercase leading-[.88] tracking-[-.035em] text-white sm:text-7xl">Uma experiência de chopp que pode virar <span className="text-[#ff7a00]">seu próximo negócio.</span></h1>
              <p className="mt-7 max-w-xl text-base leading-7 text-white/68 sm:text-lg">A Chopp ON conecta marca, operação e experiência para quem quer levar o universo do chopp a novos pontos de encontro.</p>
              <a href="#cadastro" className="mt-8 inline-flex items-center gap-2 rounded-full bg-[#ff7a00] px-6 py-4 text-sm font-extrabold text-[#151515] transition hover:bg-[#ff922d] active:scale-[.97]">Quero conhecer a franquia <ArrowRight className="size-4" /></a>
            </div>
            <div className="relative rounded-[2.2rem] border border-white/10 bg-white/[.055] p-6 sm:p-8">
              <p className="font-display text-xl font-bold uppercase text-[#ff8b27]">SMART CHOPP</p>
              <div className="mt-5 grid grid-cols-2 gap-3">
                <div className="rounded-2xl bg-[#ff7a00] p-5 text-[#171717]"><Sparkles className="size-6" /><p className="mt-10 font-display text-3xl font-black uppercase leading-none">Marca<br />presente</p></div>
                <div className="rounded-2xl border border-white/10 bg-[#191919] p-5"><ShieldCheck className="size-6 text-[#ff7a00]" /><p className="mt-10 font-display text-3xl font-black uppercase leading-none">Jornada<br />orientada</p></div>
              </div>
              <p className="mt-5 border-t border-white/10 pt-5 text-sm leading-6 text-white/55">A avaliação de disponibilidade, investimento e implantação é feita em conversa com o time comercial.</p>
            </div>
          </div>
        </section>

        <section id="modelos" className="bg-[#f5f0e8] py-18 text-[#171717] sm:py-24">
          <div className="container">
            <div className="grid gap-8 border-b border-black/10 pb-10 lg:grid-cols-[.82fr_1.18fr] lg:items-end">
              <div><Eyebrow>Modelos ChoppON</Eyebrow><h2 className="font-display text-5xl font-black uppercase leading-[.88] tracking-[-.04em] sm:text-6xl">Quatro formas de <span className="text-[#e66300]">empreender com chopp.</span></h2></div>
              <p className="max-w-xl text-base leading-7 text-black/62 sm:text-lg">Escolha o formato que melhor combina com o seu ponto, público e oportunidade de negócio.</p>
            </div>

            <div className="mt-8 grid gap-5 lg:grid-cols-2">
              {franchiseModels.map(model => {
                const Icon = modelIcons[model.id];
                const visual = modelVisuals[model.id];
                const VisualIcon = visual.icon;
                return (
                  <article key={model.id} className={`group relative isolate min-h-[34rem] overflow-hidden rounded-[2rem] border p-6 shadow-[0_16px_35px_rgba(27,22,18,.08)] transition-all duration-200 motion-safe:hover:-translate-y-1 motion-safe:hover:shadow-[0_24px_48px_rgba(27,22,18,.17)] sm:p-8 ${visual.panel}`}>
                    <p aria-hidden="true" className={`pointer-events-none absolute right-4 top-0 select-none font-display text-[9rem] font-black leading-none tracking-[-.1em] sm:text-[11rem] ${visual.number}`}>{model.number}</p>
                    <div className="relative flex h-full flex-col">
                      <div className="flex items-start justify-between gap-4"><span className={`rounded-full border px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[.16em] ${visual.chip}`}>{model.badge}</span><Icon className="size-7 text-[#ff7a00] transition-transform duration-200 motion-safe:group-hover:rotate-3 motion-safe:group-hover:scale-110" /></div>
                      <div className={`relative mt-6 h-32 overflow-hidden rounded-2xl border p-5 ${visual.surface}`}>
                        <div className="absolute -right-4 -top-4 size-28 rounded-full border-[16px] border-[#ff7a00]/35 transition-transform duration-200 motion-safe:group-hover:scale-110" />
                        <div className="relative flex h-full items-end gap-3"><div className="grid size-16 place-items-center rounded-2xl bg-[#ff7a00] text-[#171717] shadow-[0_12px_28px_rgba(255,122,0,.24)]"><VisualIcon className="size-8" /></div><div className="flex-1 border-b border-current/15 pb-2"><span className="block h-1 w-2/3 rounded-full bg-current/20" /><span className="mt-2 block h-1 w-1/2 rounded-full bg-current/15" /></div></div>
                      </div>
                      <p className="mt-7 text-xs font-extrabold uppercase tracking-[.16em] text-[#ff8b27]">{model.title}</p>
                      <h3 className="mt-3 max-w-md font-display text-4xl font-black uppercase leading-[.9] tracking-[-.035em]">{model.headline}</h3>
                      <p className="mt-4 max-w-xl text-sm leading-6 opacity-70">{model.description}</p>
                      <div className="mt-6 grid gap-5 sm:grid-cols-[1fr_auto]">
                        <div><p className="text-[10px] font-extrabold uppercase tracking-[.16em] opacity-55">Ideal para</p><div className="mt-3 flex flex-wrap gap-2">{model.idealFor.map(item => <span key={item} className={`rounded-full border px-2.5 py-1 text-[11px] font-bold ${visual.chip}`}>{item}</span>)}</div></div>
                        <div className="flex flex-row flex-wrap content-start gap-2 sm:max-w-32 sm:flex-col">{model.highlights.map(item => <span key={item} className={`rounded-full border px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-[.1em] ${visual.chip}`}>{item}</span>)}</div>
                      </div>
                      <a href="#cadastro" className={`mt-auto inline-flex w-fit items-center gap-2 pt-8 text-sm font-extrabold uppercase tracking-[.08em] transition-transform duration-150 motion-safe:group-hover:translate-x-1 ${visual.cta}`}>{model.cta} <ArrowRight className="size-4" /></a>
                    </div>
                  </article>
                );
              })}
            </div>
          </div>
        </section>

        <section className="bg-[#111111] py-18 sm:py-24">
          <div className="container grid gap-9 rounded-[2rem] border border-white/10 bg-[radial-gradient(circle_at_90%_15%,rgba(255,122,0,.3),transparent_26%),#181818] p-7 sm:p-12 lg:grid-cols-[1fr_.8fr] lg:items-end">
            <div><Eyebrow>Encontre o formato certo para o seu negócio</Eyebrow><h2 className="max-w-2xl font-display text-5xl font-black uppercase leading-[.88] tracking-[-.04em] text-white sm:text-6xl">Um modelo para <span className="text-[#ff7a00]">cada oportunidade.</span></h2><p className="mt-6 max-w-2xl text-sm leading-7 text-white/65 sm:text-base">Do ponto autônomo ao delivery, a ChoppOn foi criada para transformar diferentes espaços e oportunidades em operações de chope mais conectadas, práticas e escaláveis.</p></div>
            <div className="flex flex-col gap-3 sm:flex-row lg:flex-col"><a href="#cadastro" className="inline-flex items-center justify-center gap-2 rounded-full bg-[#ff7a00] px-6 py-4 text-sm font-extrabold text-[#171717] transition hover:bg-[#ff922d] active:scale-[.97]">Quero conhecer a franquia <ArrowRight className="size-4" /></a><WhatsAppLink source="franchise_models_commercial" label="Falar com o time comercial" /></div>
          </div>
        </section>

        <section className="bg-[#f5f0e8] py-18 text-[#171717] sm:py-24">
          <div className="container"><Eyebrow>Escolha seu modelo</Eyebrow><h2 className="max-w-2xl font-display text-5xl font-black uppercase leading-[.88] tracking-[-.04em] sm:text-6xl">Qual ChoppON combina com o seu negócio?</h2><div className="mt-9 grid gap-4 md:grid-cols-2 xl:grid-cols-4">{franchiseModelComparison.map(([title, text], index) => <article key={title} className="group rounded-3xl border border-black/10 bg-white p-5 shadow-[0_14px_28px_rgba(27,22,18,.06)] transition-all duration-200 motion-safe:hover:-translate-y-1 motion-safe:hover:shadow-[0_20px_34px_rgba(27,22,18,.13)]"><p className="font-display text-4xl font-black text-[#ff7a00]/70">0{index + 1}</p><h3 className="mt-8 font-display text-2xl font-black uppercase">{title}</h3><p className="mt-3 text-sm leading-6 text-black/60">{text}</p></article>)}</div><a href="#cadastro" className="mt-8 inline-flex items-center gap-2 rounded-full border border-black/15 px-5 py-3.5 text-sm font-extrabold text-[#171717] transition hover:border-[#ff7a00] hover:text-[#d85d00] active:scale-[.97]">Descubra o modelo ideal <ArrowRight className="size-4" /></a></div>
        </section>

        <section className="bg-[#f5f0e8] pb-18 text-[#171717] sm:pb-24"><div className="container rounded-[2rem] border border-black/10 bg-[#181818] p-7 text-white sm:p-10"><div className="grid gap-8 lg:grid-cols-[.85fr_1.15fr]"><div><Eyebrow>Presença que você pode conhecer</Eyebrow><h2 className="font-display text-5xl font-black uppercase leading-[.88]">Marca com pontos de encontro já em operação.</h2><p className="mt-5 max-w-md text-sm leading-7 text-white/60">A conversa sobre franquia parte de uma marca que já está presente em Minas Gerais — em Serra do Cipó e Sete Lagoas — e de um processo que apresenta os próximos passos com transparência.</p></div><div className="grid gap-4 sm:grid-cols-2"><article className="rounded-2xl border border-white/10 bg-white/[.04] p-5"><MapPinned className="size-6 text-[#ff7a00]" /><p className="mt-9 font-display text-3xl font-black uppercase">Serra do Cipó</p><p className="mt-3 text-sm leading-6 text-white/55">Unidade Chopp ON em Minas Gerais.</p></article><article className="rounded-2xl border border-white/10 bg-white/[.04] p-5"><CheckCircle2 className="size-6 text-[#ff7a00]" /><p className="mt-9 font-display text-3xl font-black uppercase">Sete Lagoas</p><p className="mt-3 text-sm leading-6 text-white/55">Presença da marca em outra cidade mineira.</p></article></div></div></div></section>

        <section className="bg-[#171717] py-18 sm:py-24"><div className="container grid gap-10 lg:grid-cols-[.8fr_1.2fr]"><div><Eyebrow>Do interesse à abertura</Eyebrow><h2 className="font-display text-5xl font-black uppercase leading-[.9] text-white">Um processo claro, sem <span className="text-[#ff7a00]">atalhos.</span></h2><p className="mt-5 max-w-sm text-sm leading-6 text-white/60">Cada praça tem particularidades. Por isso, a jornada começa com análise e termina com uma operação preparada para receber pessoas.</p></div><div className="grid gap-3 sm:grid-cols-2">{stages.map(([number, title, text]) => <article key={number} className="rounded-2xl border border-white/10 bg-white/[.035] p-5"><p className="font-display text-3xl font-black text-[#ff7a00]">{number}</p><h3 className="mt-7 font-display text-2xl font-bold uppercase">{title}</h3><p className="mt-3 text-sm leading-6 text-white/55">{text}</p></article>)}</div></div></section>

        <section className="bg-[#111111] py-18 sm:py-24"><div className="container grid gap-6 lg:grid-cols-3"><article className="rounded-3xl border border-white/10 bg-white/[.04] p-6"><MapPinned className="size-7 text-[#ff7a00]" /><h2 className="mt-8 font-display text-3xl font-black uppercase">Expansão consciente</h2><p className="mt-3 text-sm leading-6 text-white/60">A análise considera o contexto de cada cidade e o formato que pode fazer sentido para o ponto.</p></article><article className="rounded-3xl border border-white/10 bg-white/[.04] p-6"><CircleDollarSign className="size-7 text-[#ff7a00]" /><h2 className="mt-8 font-display text-3xl font-black uppercase">Conversas transparentes</h2><p className="mt-3 text-sm leading-6 text-white/60">A indicação de investimento e estrutura é tratada em etapa comercial, de acordo com cada projeto.</p></article><article className="rounded-3xl border border-white/10 bg-white/[.04] p-6"><CheckCircle2 className="size-7 text-[#ff7a00]" /><h2 className="mt-8 font-display text-3xl font-black uppercase">Marca para viver</h2><p className="mt-3 text-sm leading-6 text-white/60">A proposta é simples: dar identidade e qualidade a momentos em que pessoas se encontram.</p></article></div></section>

        <section id="cadastro" className="scroll-mt-24 bg-[#101010] py-18 sm:py-24"><div className="container grid gap-10 lg:grid-cols-[.8fr_1.2fr]"><div><Eyebrow>Próximo passo</Eyebrow><h2 className="font-display text-5xl font-black uppercase leading-[.9] text-white">Conte sobre seu <span className="text-[#ff7a00]">plano.</span></h2><p className="mt-6 max-w-md text-sm leading-7 text-white/60">Preencha os dados essenciais. O cadastro não exige CPF, não expõe seus dados e direciona a conversa para a oportunidade certa.</p><div className="mt-8 space-y-4 text-sm text-white/72"><p className="flex gap-3"><CheckCircle2 className="mt-0.5 size-4 shrink-0 text-[#ff7a00]" />Dados armazenados em ambiente protegido.</p><p className="flex gap-3"><CheckCircle2 className="mt-0.5 size-4 shrink-0 text-[#ff7a00]" />Contato comercial com base no seu interesse.</p><p className="flex gap-3"><CheckCircle2 className="mt-0.5 size-4 shrink-0 text-[#ff7a00]" />Sem promessa genérica de retorno ou faturamento.</p></div></div><FranchiseLeadForm /></div></section>
      </main>
    </PageShell>
  );
}
