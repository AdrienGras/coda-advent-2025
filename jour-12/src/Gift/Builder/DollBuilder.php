<?php

namespace Gift\Builder;

use Gift\Builder\Impl\GiftBuilderInterface;

class DollBuilder implements GiftBuilderInterface
{
    public function build(string $recipient): string
    {
        return "🪆 Poupée magique pour $recipient";
    }
}