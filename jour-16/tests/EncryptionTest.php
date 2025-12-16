<?php

use Email\Encryption;
use Faker\Factory as FakerFactory;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotEmpty;

/**
 * Configuration initiale avant chaque test
 * 
 * Crée une instance de la classe Encryption avec les paramètres :
 * - Nom : "Coda"
 * - Année : "2025"
 */
beforeEach(function () {
    $this->encryption = new Encryption(
        "Coda",
        "2025"
    );
});

/**
 * Test de déchiffrement d'un email
 * 
 * Vérifie que la méthode decrypt() arrive à déchiffrer correctement
 * le contenu du fichier 'email' stocké dans le dossier resources.
 * Le test vérifie que le texte déchiffré n'est pas vide et affiche
 * le résultat dans les logs.
 */
test('decrypt the email', function () {
    // Charge le fichier email chiffré et le déchiffre
    $decryptedText = $this->encryption->decrypt(
        loadFile('email')
    );

    // Vérifie que le texte déchiffré n'est pas vide
    assertNotEmpty($decryptedText);

    // Affiche le texte déchiffré dans les logs pour vérification
    error_log($decryptedText);
});

/**
 * Fonction utilitaire pour charger le contenu d'un fichier
 * 
 * Charge et retourne le contenu d'un fichier depuis le dossier resources.
 * 
 * @param string $fileName Le nom du fichier à charger (sans le chemin)
 * @return string Le contenu du fichier
 * @throws InvalidArgumentException Si le fichier n'existe pas
 */
function loadFile(string $fileName): string
{
    // Construit le chemin complet vers le fichier
    $filePath = __DIR__ . '/resources/' . $fileName;

    // Vérifie que le fichier existe
    if (!file_exists($filePath)) {
        throw new InvalidArgumentException("File not found: $fileName");
    }

    // Charge et retourne le contenu du fichier
    return file_get_contents($filePath);
}
