<?php

namespace Gift\Impl;

interface GiftDeliveryInterface
{
    public function deliver(string $gift, string $recipient): void;
}