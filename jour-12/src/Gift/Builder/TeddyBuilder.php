<?php

namespace Gift\Builder;

use Gift\Builder\Impl\GiftBuilderInterface;

class TeddyBuilder implements GiftBuilderInterface
{
    public function build(string $recipient): string
    {
        return "🧸 Ourson en peluche pour $recipient";
    }
}