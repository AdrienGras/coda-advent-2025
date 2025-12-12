<?php

namespace Gift;

use Gift\Impl\GiftWrapperInterface;

class Wrapper implements GiftWrapperInterface
{
    public function wrap(string $gift): void
    {
        usleep(3000);
    }
}