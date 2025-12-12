<?php

namespace Gift\Builder;

use Gift\Builder\Impl\GiftBuilderInterface;
use Gift\Builder\Impl\GiftBuilderRegistryInterface;

class BuilderRegistry implements GiftBuilderRegistryInterface
{
    private array $builders = [];

    public function registerBuilder(string $giftType, GiftBuilderInterface $builder): void
    {
        $this->builders[$giftType] = $builder;
    }

    public function getBuilder(string $giftType): ?GiftBuilderInterface
    {
        if (false === array_key_exists($giftType, $this->builders)) {
            throw new \InvalidArgumentException("No builder registered for gift type: $giftType");
        }

        return $this->builders[$giftType];
    }
}