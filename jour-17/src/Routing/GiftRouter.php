<?php

declare(strict_types=1);

namespace Routing;

/**
 * Routeur de cadeaux qui détermine le mode de livraison optimal.
 * 
 * Cette classe implémente la logique de routage des cadeaux en fonction de leurs
 * caractéristiques (poids, fragilité, zone de destination) pour choisir le meilleur
 * mode de transport parmi :
 * - REINDEER-EXPRESS : Livraison rapide par rennes
 * - SLED : Livraison par traîneau standard
 * - WORKSHOP-HOLD : Retenu à l'atelier (zone manquante)
 * - ERROR : Erreur (cadeau null)
 */
final class GiftRouter
{
    /**
     * Détermine le mode d'acheminement approprié pour un cadeau.
     * 
     * Logique de routage :
     * 1. Si le cadeau est null → ERROR
     * 2. Si la zone est manquante → WORKSHOP-HOLD
     * 3. Si le cadeau est fragile → routage spécial fragile
     * 4. Sinon → routage standard non-fragile
     * 
     * @param Gift|null $g Le cadeau à router (peut être null)
     * @return string Le mode d'acheminement : 'REINDEER-EXPRESS', 'SLED', 'WORKSHOP-HOLD', ou 'ERROR'
     */
    public function route(?Gift $g): string
    {
        // Cas d'erreur : cadeau null
        if ($g === null) {
            return 'ERROR';
        }

        // Vérification de la zone de destination
        if ($this->isZoneMissing($g->zone)) {
            return 'WORKSHOP-HOLD';
        }

        // Routage basé sur la fragilité
        if ($g->fragile) {
            return $this->routeFragileGift($g);
        }

        return $this->routeNonFragileGift($g);
    }

    /**
     * Vérifie si la zone de destination est manquante ou invalide.
     * 
     * Une zone est considérée manquante si elle est null ou si elle ne contient
     * que des espaces blancs après trim().
     * 
     * @param string|null $zone La zone à vérifier
     * @return bool true si la zone est manquante, false sinon
     */
    private function isZoneMissing(?string $zone): bool
    {
        return $zone === null || trim($zone) === '';
    }

    /**
     * Route un cadeau fragile selon son poids.
     * 
     * Règles pour les cadeaux fragiles :
     * - Poids ≤ 2 kg → REINDEER-EXPRESS (transport rapide et délicat)
     * - Poids > 2 kg → SLED (trop lourd pour les rennes)
     * 
     * @param Gift $g Le cadeau fragile à router
     * @return string Le mode d'acheminement
     */
    private function routeFragileGift(Gift $g): string
    {
        if ($g->weightKg <= 2.0) {
            return 'REINDEER-EXPRESS';
        }
        return 'SLED';
    }

    /**
     * Route un cadeau non-fragile selon son poids et sa zone.
     * 
     * Règles pour les cadeaux non-fragiles :
     * - Poids > 10 kg → SLED (trop lourd)
     * - Zone EU ou NA → REINDEER-EXPRESS (zones prioritaires)
     * - Autres cas → SLED (livraison standard)
     * 
     * @param Gift $g Le cadeau non-fragile à router
     * @return string Le mode d'acheminement
     */
    private function routeNonFragileGift(Gift $g): string
    {
        // Les cadeaux lourds (> 10 kg) vont par traîneau
        if ($g->weightKg > 10.0) {
            return 'SLED';
        }

        // Les zones prioritaires (EU, NA) bénéficient du service express
        if ($this->isZoneEuOrNa($g->zone)) {
            return 'REINDEER-EXPRESS';
        }

        // Par défaut, livraison standard par traîneau
        return 'SLED';
    }

    /**
     * Vérifie si la zone fait partie des zones prioritaires (Europe ou Amérique du Nord).
     * 
     * @param string $zone La zone à vérifier
     * @return bool true si la zone est EU ou NA, false sinon
     */
    private function isZoneEuOrNa(string $zone): bool
    {
        return $zone === 'EU' || $zone === 'NA';
    }
}
