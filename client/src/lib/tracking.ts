export type ConversionEvent = "whatsapp_click" | "form_start" | "form_submit_success" | "app_download_intent";

declare global {
  interface Window {
    dataLayer?: Array<Record<string, unknown>>;
    gtag?: (...args: unknown[]) => void;
    fbq?: (...args: unknown[]) => void;
  }
}

export function trackConversion(event: ConversionEvent, properties: Record<string, unknown> = {}) {
  if (typeof window === "undefined") return;

  const payload = { event, ...properties };
  window.dataLayer = window.dataLayer ?? [];
  window.dataLayer.push(payload);
  window.gtag?.("event", event, properties);

  if (event === "form_submit_success") window.fbq?.("track", "Lead", properties);
  if (event === "whatsapp_click") window.fbq?.("trackCustom", "WhatsAppClick", properties);
}
