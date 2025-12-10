import { averageWeight } from '../src/weightCalculator'; // Remplace par le chemin correct

describe('averageWeight', () => {
    it('return 6.00 for [2, 5, 7, 10] and l=4', () => {
        const weights = [2, 5, 7, 10];
        const result = averageWeight(weights, 4);
        expect(result).toBeCloseTo(6.00);
    });

    it('return 2.00 for [2] and l=1', () => {
        const weights = [2];
        const result = averageWeight(weights, 1);
        expect(result).toBeCloseTo(2.00);
    });

    it('return 0.00 for [] and l=0', () => {
        const weights: number[] = [];
        const result = averageWeight(weights, 0);
        expect(result).toBeCloseTo(0.00);
    });

    it('return 1.50 for [1, 2] and l=2', () => {
        const weights = [1, 2];
        const result = averageWeight(weights, 2);
        expect(result).toBeCloseTo(1.50);
    });
});