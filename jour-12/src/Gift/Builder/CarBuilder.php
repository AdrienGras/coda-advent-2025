<?php

namespace Gift\Builder;

use Gift\Builder\Impl\GiftBuilderInterface;

class CarBuilder implements GiftBuilderInterface
{
    public function build(string $recipient): string
    {
        return "🚗 Petite voiture pour $recipient";
    }
}