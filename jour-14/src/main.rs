use eyre::{Result, WrapErr};
use std::collections::HashSet;
use std::fs;

/// Compte le nombre de maisons uniques visitées en suivant une série d'instructions de déplacement.
///
/// # Arguments
/// * `instructions` - Une chaîne de caractères contenant les directions (N, S, E, W)
///
/// # Returns
/// Le nombre de maisons uniques visitées (position de départ incluse)
///
/// # Complexité algorithmique
/// * Temps: O(n) où n est la longueur des instructions
///   - On parcourt chaque caractère une fois
///   - Les opérations d'insertion dans le HashSet sont O(1) en moyenne
/// * Espace: O(n) dans le pire cas
///   - Le HashSet peut contenir jusqu'à n+1 positions uniques si tous les déplacements
///     mènent à de nouvelles maisons
///
/// # Errors
/// Retourne une erreur si un caractère invalide est rencontré dans les instructions
fn count_unique_houses(instructions: &str) -> Result<usize> {
    // HashSet pour stocker toutes les positions visitées
    // Utilise un tuple (x, y) comme clé pour représenter chaque position
    let mut visited = HashSet::new();

    // Position initiale du livreur
    let mut x = 0;
    let mut y = 0;

    // Enregistre la position de départ
    visited.insert((x, y));

    // Parcourt chaque instruction de déplacement
    // Complexité: O(n) où n = nombre d'instructions
    for c in instructions.chars() {
        // Met à jour la position selon la direction
        match c {
            'N' => y += 1, // Nord: augmente y
            'S' => y -= 1, // Sud: diminue y
            'E' => x += 1, // Est: augmente x
            'W' => x -= 1, // Ouest: diminue x
            _ => eyre::bail!(
                "Caractère invalide '{}' dans les instructions (position: {}, {})",
                c,
                x,
                y
            ),
        }

        // Ajoute la nouvelle position au set (ignore si déjà visitée)
        // Complexité de l'insertion: O(1) en moyenne
        visited.insert((x, y));
    }

    // Retourne le nombre de positions uniques visitées
    // Complexité: O(1)
    Ok(visited.len())
}

/// Point d'entrée principal du programme
///
/// Lit les instructions depuis le fichier "steps" et calcule le nombre de maisons uniques visitées.
///
/// # Complexité globale
/// * Temps: O(n) où n est la taille du fichier
///
/// # Errors
/// Retourne une erreur si le fichier ne peut pas être lu ou si les instructions sont invalides
fn main() -> Result<()> {
    let instructions =
        fs::read_to_string("steps").wrap_err("Impossible de lire le fichier 'steps'")?;

    let result = count_unique_houses(&instructions)
        .wrap_err("Erreur lors du traitement des instructions")?;

    println!("Nombre de maisons uniques visitées : {}", result);
    Ok(())
}

/// Module de tests unitaires
#[cfg(test)]
mod tests {
    use super::*;

    /// Test avec une chaîne vide
    /// Vérifie que la position de départ compte comme une maison visitée
    #[test]
    fn test_empty() {
        assert_eq!(count_unique_houses("").unwrap(), 1);
    }

    /// Test avec un seul déplacement
    /// Vérifie que 2 maisons sont visitées (départ + arrivée)
    #[test]
    fn test_single_move() {
        assert_eq!(count_unique_houses("N").unwrap(), 2);
    }

    /// Test avec une séquence de déplacements sans retour
    /// NNESESW devrait visiter 8 maisons uniques
    #[test]
    fn test_example_1() {
        assert_eq!(count_unique_houses("NNESESW").unwrap(), 8);
    }

    /// Test avec des déplacements qui revisitent des positions
    /// NNSS: (0,0) -> (0,1) -> (0,2) -> (0,1) -> (0,0)
    /// Devrait compter 3 maisons uniques: (0,0), (0,1), (0,2)
    #[test]
    fn test_example_2() {
        assert_eq!(count_unique_houses("NNSS").unwrap(), 3);
    }

    /// Test avec un caractère invalide
    /// Vérifie que la fonction retourne une erreur pour un caractère non reconnu
    #[test]
    fn test_invalid_character() {
        assert!(count_unique_houses("NXS").is_err());
    }
}
