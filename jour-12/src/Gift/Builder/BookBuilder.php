<?php

namespace Gift\Builder;

use Gift\Builder\Impl\GiftBuilderInterface;

class BookBuilder implements GiftBuilderInterface
{
    public function build(string $recipient): string
    {
        return "📚 Livre enchanté pour $recipient";
    }
}