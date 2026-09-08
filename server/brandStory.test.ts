import { readFileSync } from "node:fs";
import { describe, expect, it } from "vitest";

const brandStory = readFileSync(new URL("../client/src/components/BrandStory.tsx", import.meta.url), "utf8");

describe("brand story section", () => {
  it("presents the institutional narrative and the four ChoppOn models", () => {
    expect(brandStory).toContain("Não é apenas chopp.");
    expect(brandStory).toContain("É uma marca para");
    expect(brandStory).toContain("CHOPPON SMART");
    expect(brandStory).toContain("CHOPPON SMART COMPACT");
    expect(brandStory).toContain("CHOPPON STATION");
    expect(brandStory).toContain("CHOPPON DELIVERY");
  });

  it("links brand CTAs to franchise and commercial journeys", () => {
    expect(brandStory).toContain('href="/franquia#cadastro"');
    expect(brandStory).toContain('href="/franquia"');
    expect(brandStory).toContain('source="brand_story_final"');
  });

  it("uses legally safe wording for the pending INPI trademark process", () => {
    expect(brandStory).toContain("Pedido de registro de marca no INPI");
    expect(brandStory).toContain("Processo nº 937670413");
    expect(brandStory).toContain("Processo de registro em andamento.");
    expect(brandStory).toContain("não declara concessão definitiva de registro ou patente");
    expect(brandStory).not.toContain("Marca registrada no INPI");
    expect(brandStory).not.toContain("Marca patenteada");
  });
});
