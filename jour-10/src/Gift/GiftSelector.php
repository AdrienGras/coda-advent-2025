<?php
declare(strict_types=1);
namespace Gift;

/**
 * Gift selector that determines which gift a child should receive
 * based on their age, behavior, and kindness score.
 */
class GiftSelector
{
    /**
     * Selects an appropriate gift for a child based on their characteristics.
     * 
     * The selection process:
     * 1. Filters only feasible gift requests
     * 2. Returns null for naughty children
     * 3. Uses different criteria for children under 14 vs. 14 and older
     * 
     * @param Child $child The child to select a gift for
     * @return string|null The selected gift name, or null if no gift should be given
     */
    public static function selectGiftFor(Child $child): ?string
    {
        // Early exit if the child is naughty
        if ($child->behavior === Behavior::NAUGHTY) {
            // CHEH !
            return null;
        }

        // Early exit if there are no gift requests
        if (empty($child->giftRequests)) {
            // Oops, seems that LA POSTE did not deliver the wishlist...
            return null;
        }

        // Filter gift requests to only include feasible ones
        $feasibleGifts = array_filter(
            $child->giftRequests,
            fn(GiftRequest $gift) => $gift->isFeasible
        );

        // Extract gift names from feasible requests
        $giftNames = array_map(
            fn(GiftRequest $gift) => $gift->giftName,
            $feasibleGifts
        );

        // No gift if there are no feasible options
        if (empty($giftNames)) {
            return null;
        }

        // Apply age and behavior-based selection rules
        return match (true) {
            $child->age < 14 => self::selectGiftForUnder14($child, $giftNames),
            $child->age >= 14 => self::selectGiftFor14Plus($child, $giftNames),
            default => throw new \LogicException("What age is this ? Dr. Who is that you ?"),
        };
    }

    /**
     * Selects a gift for children under 14 years old.
     * 
     * Selection logic:
     * - NICE children get the first gift from the list
     * - NORMAL children get the last gift from the list
     * - Others (NAUGHTY) get nothing
     * 
     * @param Child $child The child to select for
     * @param array $giftNames Array of feasible gift names
     * @return string|null The selected gift name or null
     */
    private static function selectGiftForUnder14(Child $child, array $giftNames): ?string
    {
        return match ($child->behavior) {
            Behavior::NORMAL => end($giftNames),   // Last gift in the list
            Behavior::NICE => reset($giftNames),   // First gift in the list
            default => throw new \LogicException("Naughty children should have been filtered out earlier, so... what ?"),
        };
    }

    /**
     * Selects a gift for children aged 14 and older.
     * 
     * Additional requirement: kindness score must be above 0.5
     * 
     * Selection logic:
     * - Must have kindness score > 0.5
     * - NICE children get the first gift from the list
     * - NORMAL children get the last gift from the list
     * - Others (NAUGHTY) get nothing
     * 
     * @param Child $child The child to select for
     * @param array $giftNames Array of feasible gift names
     * @return string|null The selected gift name or null
     */
    private static function selectGiftFor14Plus(Child $child, array $giftNames): ?string
    {
        // Teenagers need a minimum kindness score to receive any gift
        if ($child->kindnessScore <= 0.5) {
            return null;
        }

        return match ($child->behavior) {
            Behavior::NORMAL => end($giftNames),   // Last gift in the list
            Behavior::NICE => reset($giftNames),   // First gift in the list
            default => throw new \LogicException("Naughty children should have been filtered out earlier, so... what ?"),
        };
    }
}
