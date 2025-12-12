<?php

namespace Gift;

use Gift\Impl\GiftDeliveryInterface;

class Delivery implements GiftDeliveryInterface
{
    public function deliver(string $gift, string $recipient): void
    {
        usleep(4000);
        if (rand(0, 10) > 8) {
            throw new \Exception("Erreur de livraison : le traîneau est tombé en panne.");
        }
    }
}