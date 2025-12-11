<?php

namespace Navigation;

/**
 * Building navigation calculator that determines floor position
 * based on signal stream characters.
 */
class Building
{
    /**
     * Calculates the final floor number based on a signal stream.
     * 
     * Rules:
     * - If the signal contains an elf emoji (🧝), special scoring applies:
     *   '(' moves down 2 floors (-2)
     *   ')' moves up 3 floors (+3)
     * - If no elf emoji is present, normal scoring applies:
     *   '(' moves up 1 floor (+1)
     *   ')' moves down 1 floor (-1)
     * - Other characters are ignored
     * 
     * @param string $signalStream The input signal containing parentheses and possibly an elf emoji
     * @return int The final floor number (can be negative for basement levels)
     */
    public static function whichFloor(string $signalStream): int
    {
        // Check if the signal stream contains an elf emoji
        $containsElf = str_contains($signalStream, "🧝");

        // Set scoring rules based on elf presence
        $values = $containsElf ? ['(' => -2, ')' => 3] : ['(' => 1, ')' => -1];

        // Start at ground floor (0)
        $result = 0;

        // Using strlen for accurate length calculation
        $length = \strlen($signalStream);

        // Process each character and accumulate floor changes
        // missing my FP accumulators here... :'(
        $iterator = 0;
        for ($iterator = 0; $iterator < $length; $iterator++) {
            // Using array access for character retrieval
            // This is more efficient than mb_substr for single character access
            // and more efficient than str_split which creates an array of all characters
            $char = $signalStream[$iterator];

            // Accumulate floor changes based on character and scoring rules
            // If character is not in values, default to 0 (ignore it, null accumulation)
            $result += $values[$char] ?? 0;
        }

        return $result;
    }
}