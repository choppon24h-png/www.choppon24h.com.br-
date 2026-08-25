export type HeadMeta = {
  title: string;
  description: string;
  canonicalPath?: string;
  ogImage?: string;
  ogImageAlt?: string;
  noindex?: boolean;
  notFound?: boolean;
};

const site = "Chopp ON";
const logo = "/manus-storage/chopp-on-logo_ee1c339e.png";

export function prefetchForPath(url: string): HeadMeta {
  const path = (url.split("?")[0].replace(/\/+$/, "") || "/").toLowerCase();
  const shared = { ogImage: logo, ogImageAlt: "Logo Chopp ON" };

  if (path === "/") return {
    title: "Chopp ON | Experiências, franquias e chopp delivery",
    description: "Conheça a Chopp ON: SMART CHOPP para novos negócios e chopp delivery para celebrar com qualidade.",
    canonicalPath: "/",
    ...shared,
  };
  if (path === "/franquia") return {
    title: "Franquia de chopp autônomo | Chopp ON",
    description: "Leve a SMART CHOPP para sua cidade com tecnologia própria, operação inteligente e suporte Chopp ON.",
    canonicalPath: "/franquia",
    ...shared,
  };
  if (path === "/franquia/obrigado") return {
    title: "Cadastro recebido | Chopp ON",
    description: "Recebemos seu interesse em franquia. O time Chopp ON entrará em contato.",
    canonicalPath: "/franquia/obrigado",
    noindex: true,
    ...shared,
  };
  if (path === "/delivery") return {
    title: "Chopp delivery para festas e eventos | Chopp ON",
    description: "Barris de chopp para sua festa em Jaboticatubas, Serra do Cipó e Baldim. Consulte pelo WhatsApp.",
    canonicalPath: "/delivery",
    ...shared,
  };
  if (path === "/aplicativo") return {
    title: "Aplicativo Chopp ON | Consumo, pontos e unidades",
    description: "Conheça o aplicativo do cliente Chopp ON: consumo, pontos por consumação, ranking e unidades da marca.",
    canonicalPath: "/aplicativo",
    ...shared,
  };
  if (path === "/franqueado") return {
    title: "Área do Franqueado | Chopp ON",
    description: "Conheça a visão da operação conectada Chopp ON: gestão remota, aplicativo do franqueado e alertas inteligentes.",
    canonicalPath: "/franqueado",
    ...shared,
  };
  if (path === "/admin" || path.startsWith("/admin/")) return {
    title: site,
    description: "Área restrita Chopp ON.",
    noindex: true,
    ...shared,
  };
  return { title: site, description: "Chopp ON — sua melhor experiência em chopp.", noindex: true, notFound: true, ...shared };
}
