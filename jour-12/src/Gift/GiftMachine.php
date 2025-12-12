<?php

namespace Gift;

use Gift\Builder\Impl\GiftBuilderRegistryInterface;
use Gift\Impl\ErrorHandlerInterface;
use Gift\Impl\GiftDeliveryInterface;
use Gift\Impl\GiftRibbonInterface;
use Gift\Impl\GiftWrapperInterface;
use Gift\Impl\LoggerInterface;

class GiftMachine
{
    public function __construct(
        private GiftBuilderRegistryInterface $builderRegistry,
        private GiftWrapperInterface $wrapper,
        private GiftRibbonInterface $ribbon,
        private GiftDeliveryInterface $delivery,
        private LoggerInterface $logger,
        private ErrorHandlerInterface $errorHandler
    ) {
    }

    public function createGift(string $giftType, string $recipient): string
    {
        try {
            $this->logger->log("Démarrage de la création du cadeau pour $recipient");

            $builder = $this->builderRegistry->getBuilder($giftType);

            $gift = $builder->build($recipient);
            $this->logger->log("Construction du cadeau...");

            $this->wrapper->wrap($gift);
            $this->logger->log("Emballage du cadeau : $gift");

            $this->ribbon->addRibbon($gift);
            $this->logger->log("Ajout du ruban magique sur : $gift");

            $this->delivery->deliver($gift, $recipient);
            $this->logger->log("Livraison en cours vers l'atelier de distribution...");

            $this->logger->log("Cadeau prêt pour $recipient : $gift");
            return $gift;

        } catch (\Exception $e) {
            $this->errorHandler->handle($e->getMessage());
            return "Échec de la création du cadeau pour $recipient";
        }
    }
}