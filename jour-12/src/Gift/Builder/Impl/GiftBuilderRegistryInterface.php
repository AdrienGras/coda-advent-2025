<?php

namespace Gift\Builder\Impl;

interface GiftBuilderRegistryInterface
{

    public function registerBuilder(string $giftType, GiftBuilderInterface $builder): void;

    public function getBuilder(string $giftType): ?GiftBuilderInterface;
}