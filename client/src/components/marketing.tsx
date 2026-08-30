import type { ReactNode } from "react";
import { useState } from "react";
import { Link, useLocation } from "wouter";
import { ArrowUpRight, Instagram, Menu, MessageCircle, X } from "lucide-react";
import { trackConversion } from "@/lib/tracking";

export const LOGO_URL = "/manus-storage/chopp-on-logo_ee1c339e.png";
export const WHATSAPP_URL = "https://wa.me/5511991748555?text=Olá!%20Quero%20falar%20com%20a%20Chopp%20ON.";

export function BrandLogo({ compact = false }: { compact?: boolean }) {
  return (
    <Link href="/" className={`inline-flex items-center justify-center overflow-hidden rounded-xl bg-white shadow-[0_8px_30px_rgba(0,0,0,.17)] ${compact ? "h-14 w-36 p-0" : "h-24 w-48 p-1"}`} aria-label="Chopp ON — página inicial">
      <img src={LOGO_URL} alt="Chopp ON" width={compact ? 142 : 186} height={compact ? 56 : 92} className={`h-full w-full max-w-none object-contain ${compact ? "scale-[1.18]" : "scale-[1.08]"}`} />
    </Link>
  );
}

const navItems = [
  { href: "/franquia", label: "Franquias" },
  { href: "/delivery", label: "Chopp delivery" },
  { href: "/aplicativo", label: "Aplicativo" },
  { href: "/franqueado", label: "Área do franqueado" },
  { href: "/#marca", label: "A marca" },
];

export function PublicHeader() {
  const [isOpen, setIsOpen] = useState(false);
  const [location] = useLocation();

  return (
    <header className="sticky top-0 z-40 border-b border-white/10 bg-[#111111]/95 backdrop-blur-xl">
      <div className="container flex h-20 items-center justify-between gap-6">
        <BrandLogo compact />
        <nav className="hidden items-center gap-7 lg:flex" aria-label="Navegação principal">
          {navItems.map(item => (
            <a key={item.href} href={item.href} className={`text-sm font-bold transition-colors hover:text-[#ff7a00] ${location === item.href ? "text-[#ff7a00]" : "text-white/75"}`}>
              {item.label}
            </a>
          ))}
        </nav>
        <div className="hidden lg:block">
          <Link href="/franquia#cadastro" className="inline-flex items-center gap-2 rounded-full bg-[#ff7a00] px-5 py-3 text-sm font-extrabold text-[#181818] transition-transform duration-150 hover:bg-[#ff922d] active:scale-[.97]">
            Quero empreender <ArrowUpRight className="size-4" aria-hidden="true" />
          </Link>
        </div>
        <button type="button" className="inline-flex rounded-lg p-2 text-white lg:hidden" onClick={() => setIsOpen(value => !value)} aria-label={isOpen ? "Fechar menu" : "Abrir menu"} aria-expanded={isOpen}>
          {isOpen ? <X className="size-6" /> : <Menu className="size-6" />}
        </button>
      </div>
      {isOpen && (
        <div className="border-t border-white/10 bg-[#161616] px-4 py-5 lg:hidden">
          <nav className="container flex flex-col gap-2" aria-label="Navegação móvel">
            {navItems.map(item => <a key={item.href} href={item.href} onClick={() => setIsOpen(false)} className="rounded-xl px-4 py-3 font-bold text-white/80 hover:bg-white/5 hover:text-[#ff7a00]">{item.label}</a>)}
            <Link href="/franquia#cadastro" onClick={() => setIsOpen(false)} className="mt-2 rounded-xl bg-[#ff7a00] px-4 py-3 text-center font-extrabold text-[#171717]">Quero empreender</Link>
          </nav>
        </div>
      )}
    </header>
  );
}

export function WhatsAppLink({ label = "Falar no WhatsApp", source }: { label?: string; source: string }) {
  return (
    <a href={WHATSAPP_URL} target="_blank" rel="noreferrer" onClick={() => trackConversion("whatsapp_click", { source })} className="inline-flex items-center justify-center gap-2 rounded-full bg-[#25D366] px-5 py-3.5 text-sm font-extrabold text-[#0d2617] transition-transform duration-150 hover:brightness-110 active:scale-[.97]">
      <MessageCircle className="size-4" aria-hidden="true" /> {label}
    </a>
  );
}

export function PageShell({ children }: { children: ReactNode }) {
  return <div className="min-h-screen overflow-x-hidden bg-[#111111] text-white"><PublicHeader />{children}<SiteFooter /><FloatingWhatsApp /></div>;
}

export function FloatingWhatsApp() {
  return <a href={WHATSAPP_URL} target="_blank" rel="noreferrer" onClick={() => trackConversion("whatsapp_click", { source: "floating_button" })} className="fixed bottom-5 right-5 z-30 inline-flex items-center gap-2 rounded-full bg-[#25D366] px-4 py-3 text-xs font-extrabold text-[#102a19] shadow-[0_14px_34px_rgba(0,0,0,.32)] transition-transform duration-150 hover:-translate-y-1 active:scale-[.97]" aria-label="Falar com a Chopp ON pelo WhatsApp"><MessageCircle className="size-4" /> Fale com a Chopp ON</a>;
}

export function SiteFooter() {
  return (
    <footer className="border-t border-white/10 bg-[#0c0c0c] py-12">
      <div className="container grid gap-10 md:grid-cols-[1.1fr_.9fr_.9fr]">
        <div>
          <BrandLogo compact />
          <p className="mt-5 max-w-sm text-sm leading-6 text-white/55">Experiências em chopp para quem quer celebrar bem e empreender com uma marca de presença.</p>
        </div>
        <div>
          <p className="font-display text-lg font-bold uppercase tracking-wide text-white">Navegue</p>
          <div className="mt-4 flex flex-col gap-2 text-sm text-white/60"><Link href="/franquia" className="hover:text-[#ff7a00]">Franquias</Link><Link href="/delivery" className="hover:text-[#ff7a00]">Chopp delivery</Link><Link href="/aplicativo" className="hover:text-[#ff7a00]">Aplicativo</Link><Link href="/franqueado" className="hover:text-[#ff7a00]">Área do franqueado</Link><Link href="/admin" className="hover:text-[#ff7a00]">Área restrita</Link></div>
        </div>
        <div>
          <p className="font-display text-lg font-bold uppercase tracking-wide text-white">Contato</p>
          <div className="mt-4 flex flex-col gap-3 text-sm text-white/60"><WhatsAppLink source="footer" label="Atendimento no WhatsApp" /><a href="https://www.instagram.com/choppon24h.oficial" target="_blank" rel="noreferrer" className="inline-flex items-center gap-2 hover:text-[#ff7a00]"><Instagram className="size-4" /> @choppon24h.oficial</a></div>
        </div>
      </div>
      <div className="container mt-10 border-t border-white/10 pt-6 text-xs text-white/35">© {new Date().getFullYear()} Chopp ON. Todos os direitos reservados.</div>
    </footer>
  );
}

export function Eyebrow({ children }: { children: ReactNode }) {
  return <p className="mb-4 inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-[.22em] text-[#ff8b27]"><span className="h-px w-7 bg-[#ff7a00]" />{children}</p>;
}

export function JsonLd({ data }: { data: Record<string, unknown> }) {
  return <script type="application/ld+json" dangerouslySetInnerHTML={{ __html: JSON.stringify(data) }} />;
}
