<?php

declare(strict_types=1);

namespace Routing;

/**
 * Représente un cadeau à acheminer avec ses caractéristiques de livraison.
 * 
 * Cette classe immutable (readonly properties) encapsule les informations
 * nécessaires pour déterminer le mode d'acheminement optimal d'un cadeau.
 */
final class Gift
{
    /**
     * Constructeur du cadeau.
     * 
     * @param float $weightKg Le poids du cadeau en kilogrammes
     * @param bool $fragile Indique si le cadeau est fragile (nécessite un traitement spécial)
     * @param string|null $zone La zone de destination (ex: 'EU', 'NA') ou null si non définie
     */
    public function __construct(
        public readonly float $weightKg,
        public readonly bool $fragile,
        public readonly ?string $zone
    ) {}

    /**
     * Retourne une représentation textuelle du cadeau pour le débogage.
     * 
     * @return string Représentation lisible du cadeau avec ses propriétés
     */
    public function __toString(): string
    {
        return "Gift{w={$this->weightKg}, fragile={$this->fragile}, zone='{$this->zone}'}";
    }
}
