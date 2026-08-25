import { useEffect } from "react";
import { useLocation } from "wouter";
import { prefetchForPath } from "@/ssr/prefetch";

function setMeta(selector: string, attribute: "name" | "property", key: string, content: string) {
  let element = document.head.querySelector(selector) as HTMLMetaElement | null;
  if (!element) {
    element = document.createElement("meta");
    element.setAttribute(attribute, key);
    document.head.appendChild(element);
  }
  element.content = content;
}

export function RouteHead() {
  const [location] = useLocation();
  useEffect(() => {
    const head = prefetchForPath(location);
    document.title = head.title;
    setMeta('meta[name="description"]', "name", "description", head.description);
    setMeta('meta[property="og:title"]', "property", "og:title", head.title);
    setMeta('meta[property="og:description"]', "property", "og:description", head.description);
  }, [location]);
  return null;
}
