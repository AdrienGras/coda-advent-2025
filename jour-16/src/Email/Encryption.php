<?php

namespace Email;

use RuntimeException;

/**
 * Classe de chiffrement/déchiffrement d'emails
 * 
 * Utilise l'algorithme AES-256-CBC pour déchiffrer des messages encodés en base64.
 * La clé de chiffrement est générée à partir d'un nom et d'une année.
 */
class Encryption
{
    /**
     * Clé de chiffrement générée par SHA-256
     * @var string
     */
    private string $key;

    /**
     * Vecteur d'initialisation (IV) généré par MD5
     * @var string
     */
    private string $iv;

    /**
     * Constructeur de la classe Encryption
     * 
     * Initialise la clé et le vecteur d'initialisation à partir d'un nom et d'une année.
     * - La clé est générée par un hash SHA-256 du nom (32 bytes)
     * - Le IV est généré par un hash MD5 de l'année (16 bytes)
     * 
     * @param string $name Le nom utilisé pour générer la clé de chiffrement
     * @param string $year L'année utilisée pour générer le vecteur d'initialisation
     */
    public function __construct(
        string $name,
        string $year
    ) {
        // Génère une clé de 32 bytes (256 bits) à partir du nom
        $this->key = hash('sha256', $name, true);
        // Génère un IV de 16 bytes (128 bits) à partir de l'année
        $this->iv = hash('md5', $year, true);
    }

    /**
     * Déchiffre un texte chiffré
     * 
     * Prend un texte encodé en base64 et le déchiffre en utilisant l'algorithme
     * AES-256-CBC avec la clé et le IV définis lors de l'instanciation.
     * 
     * @param string $encryptedText Le texte chiffré et encodé en base64
     * @return string Le texte déchiffré en clair
     * @throws RuntimeException Si le déchiffrement échoue
     */
    public function decrypt(string $encryptedText): string
    {
        // Décode le texte depuis base64 vers les données binaires chiffrées
        $encryptedData = base64_decode($encryptedText);

        // Déchiffre les données avec AES-256-CBC
        $decrypted = openssl_decrypt(
            $encryptedData,           // Les données chiffrées
            'AES-256-CBC',            // Algorithme de chiffrement
            $this->key,               // La clé de déchiffrement (256 bits)
            OPENSSL_RAW_DATA,         // Format des données en entrée (binaire brut)
            $this->iv                 // Le vecteur d'initialisation (128 bits)
        );

        // Vérifie que le déchiffrement a réussi
        if ($decrypted === false) {
            throw new RuntimeException("Failed to decrypt data");
        }

        return $decrypted;
    }
}
