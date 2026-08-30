import { describe, expect, it } from "vitest";
import { franchiseLeadInputSchema } from "../shared/lead";

const validLead = {
  fullName: "Ana Martins",
  email: "ana@example.com",
  phone: "(31) 99999-0000",
  city: "Belo Horizonte",
  state: "MG",
  investmentRange: "100k-to-200k" as const,
  preferredModel: "smart-chopp" as const,
  consent: true as const,
};

describe("franchise lead input", () => {
  it("accepts a concise lead with explicit consent", () => {
    expect(franchiseLeadInputSchema.safeParse(validLead).success).toBe(true);
  });

  it("rejects an invalid email and a missing LGPD consent", () => {
    expect(
      franchiseLeadInputSchema.safeParse({ ...validLead, email: "invalid", consent: false }).success,
    ).toBe(false);
  });

  it("rejects bot honeypot submissions", () => {
    expect(franchiseLeadInputSchema.safeParse({ ...validLead, website: "spam.example" }).success).toBe(false);
  });
});
