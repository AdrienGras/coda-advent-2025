/**
 * Calculates the average weight of items from a weighted list.
 * 
 * This function computes the average by summing up to a specified number of weights
 * from the input array and dividing by the limit parameter. If the array contains
 * fewer elements than the limit, only the available weights are summed, but the
 * division is still performed using the original limit value.
 * 
 * @param w - Array of numerical weight values to be averaged
 * @param l - The limit/count to determine how many weights to consider and use as divisor
 * 
 * @returns The calculated average weight. Returns 0 if the limit is non-positive or the array is empty.
 * 
 * @example
 * ```typescript
 * averageWeight([10, 20, 30], 3)  // Returns 20 (sum: 60, divisor: 3)
 * averageWeight([10, 20], 5)      // Returns 6 (sum: 30, divisor: 5)
 * averageWeight([], 3)            // Returns 0
 * averageWeight([10, 20, 30], 0)  // Returns 0
 * ```
 * 
 * @remarks
 * Note: If the weights array has fewer elements than the limit, the function will
 * sum all available weights but still divide by the limit parameter, which may result
 * in a lower average than the actual mean of the array elements.
 */
export function averageWeight(w: number[], l: number): number {
    // Return 0 for invalid inputs: non-positive limit or empty array
    if (l <= 0 || w.length === 0) return 0;

    // Sum accumulator for weight values
    let s = 0;

    // Determine how many elements to process (minimum of limit and array length)
    const limit = Math.min(l, w.length);

    // Sum up the weights up to the limit
    for (let i = 0; i < limit; i++) {
        s += w[i];
    }

    // Calculate and return the average by dividing sum by the original limit parameter
    return s / l;
}