<?php
declare(strict_types=1);

namespace Gift;

class Child
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly int $age,
        public readonly Behavior $behavior,
        public readonly array $giftRequests = [],
        public readonly float $kindnessScore = 0.5,
    ) {
    }
}
