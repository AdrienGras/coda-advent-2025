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

        // Split the signal stream into individual characters
        $streamAsArray = str_split($signalStream);

        // Process each character and accumulate floor changes
        foreach ($streamAsArray as $c) {
            $result += $values[$c] ?? 0;  // Add value or 0 if character not recognized
        }

        return $result;
    }
}