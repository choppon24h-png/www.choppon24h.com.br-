import { readFileSync } from "node:fs";
import { describe, expect, it } from "vitest";

const marketing = readFileSync(new URL("../client/src/components/marketing.tsx", import.meta.url), "utf8");
const qualificationPath = 'href="/franquia/franquia-form.html"';

describe("header franchise qualification CTA", () => {
  it("sends the desktop and mobile Quero empreender CTAs directly to the public qualification form", () => {
    expect(marketing.match(new RegExp(qualificationPath, "g"))?.length).toBe(2);
    expect(marketing).toContain("Quero empreender");
    expect(marketing).not.toContain('href="/franquia#cadastro"');
  });
});
