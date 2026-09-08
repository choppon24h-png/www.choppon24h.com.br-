import { describe, expect, it } from "vitest";
import { franchiseModelComparison, franchiseModels } from "../client/src/data/franchiseModels";

describe("franchiseModels", () => {
  it("presents the four ChoppON models as a complete portfolio", () => {
    expect(franchiseModels).toHaveLength(4);
    expect(franchiseModels.map(model => model.title)).toEqual([
      "CHOPPON SMART",
      "CHOPPON SMART COMPACT",
      "CHOPPON STATION",
      "CHOPPON DELIVERY",
    ]);
    expect(franchiseModels.every(model => model.idealFor.length > 0 && model.highlights.length === 4)).toBe(true);
    expect(franchiseModelComparison).toHaveLength(4);
  });
});
