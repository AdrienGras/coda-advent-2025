<?php

namespace Gift\Builder\Impl;

interface GiftBuilderInterface
{
    public function build(string $recipient): string;
}