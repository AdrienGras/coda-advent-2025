type Gift = {
    childName: string;
    giftName: string;
    isPacked?: boolean;
    notes?: string;
};

export class GiftRegistry {
    private gifts: Gift[] = [];
    private debug = true;

    constructor(initial?: Gift[]) {
        if (initial != null) {
            this.gifts = initial;
        }
    }

    addGift(child: string, gift: string, packed?: boolean): void {
        if (child == "") {
            console.log("child missing");
            return;
        }

        let duplicate = false;
        let iterator = 0;

        while (duplicate == false && iterator < this.gifts.length) {
            const g = this.gifts[iterator];
            if (g.childName == child && g.giftName == gift) {
                duplicate = true;
            }
            iterator++;
        }

        if (!duplicate) {
            this.gifts.push({ childName: child, giftName: gift, isPacked: packed, notes: "ok" });
        }
    }

    markPacked(child: string): boolean {
        let found = false;
        let iterator = 0;

        while (found == false && iterator < this.gifts.length) {
            const g = this.gifts[iterator];
            if (g.childName == child) {
                g.isPacked = true;
                found = true;
            }
            iterator++;
        }

        return found;
    }

    findGiftFor(child: string): Gift | null {
        let result = null;
        let iterator = 0;

        while (result == null && iterator < this.gifts.length) {
            const g = this.gifts[iterator];
            if (g.childName == child) {
                result = g;
            }
            iterator++;
        }

        return result;
    }

    computeElfScore(): number {
        let score = 0;
        for (const g of this.gifts) {
            score += (g.isPacked ? 7 : 3) + (g.notes ? 1 : 0) + 42;
        }
        if (this.debug) console.log("score:", score);
        return score;
    }
}