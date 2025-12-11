<?php
declare(strict_types=1);
namespace Tests;

use Gift\Behavior;
use Gift\Child;
use Gift\GiftRequest;

class ChildBuilder
{
    private Behavior $behavior = Behavior::NICE;
    private array $giftRequests = [];
    private int $age = 9;
    private float $kindness = 0.0;

    public static function aChild(): self
    {
        return new self();
    }

    public function nice(): self
    {
        $this->behavior = Behavior::NICE;
        return $this;
    }

    public function normal(): self
    {
        $this->behavior = Behavior::NORMAL;
        return $this;
    }

    public function naughty(): self
    {
        $this->behavior = Behavior::NAUGHTY;
        return $this;
    }

    public function age(int $age): self
    {
        $this->age = $age;
        return $this;
    }

    public function kindness(float $kindness): self
    {
        $this->kindness = $kindness;
        return $this;
    }

    public function requestingFeasibleGift(string $giftName = 'An feasible gift'): self
    {
        $this->giftRequests[] = new GiftRequest($giftName, true);
        return $this;
    }

    public function requestingInfeasibleGift(string $giftName = 'An infeasible gift'): self
    {
        $this->giftRequests[] = new GiftRequest($giftName, false);
        return $this;
    }

    public function build(): Child
    {
        return new Child('Jane', 'Doe', $this->age, $this->behavior, $this->giftRequests, $this->kindness);
    }
}
