<?php

namespace Gift;

use Gift\Impl\GiftRibbonInterface;

class Ribbon implements GiftRibbonInterface
{
    public function addRibbon(string $gift): void
    {
        usleep(2000);
    }
}