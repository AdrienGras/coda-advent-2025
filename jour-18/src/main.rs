// Importation des types pour la gestion d'erreurs avec eyre
use eyre::{Context, Result};
use std::{
    fs::File,
    io::{BufRead, BufReader},
};

/// Convertit une chaîne GQS (système de notation quinaire avec symboles) en nombre décimal
///
/// Le système GQS utilise une base 5 avec les symboles suivants :
/// - ☃ = -2
/// - ❄ = -1
/// - 0 = 0
/// - * = 1
/// - ✦ = 2
///
/// # Complexité
/// - Temps : O(n) où n est la longueur de la chaîne GQS
/// - Espace : O(1) - utilise uniquement quelques variables locales
///
/// # Arguments
/// * `gqs` - Une chaîne contenant des symboles GQS
///
/// # Retour
/// * `Result<i32>` - Le nombre décimal correspondant ou une erreur si un symbole est invalide
fn gqs_to_decimal(gqs: &str) -> Result<i32> {
    let mut decimal = 0;
    let mut power = 1;

    // Parcours de droite à gauche (chiffres de poids faible à poids fort)
    // O(n) où n est le nombre de caractères
    for c in gqs.chars().rev() {
        // Mapping des symboles vers leur valeur - O(1)
        let value = match c {
            '☃' => -2,
            '❄' => -1,
            '0' => 0,
            '*' => 1,
            '✦' => 2,
            _ => eyre::bail!("Symbole GQS invalide : '{}'", c),
        };
        // Calcul de la valeur décimale en base 5
        decimal += value * power;
        power *= 5; // Puissance suivante en base 5
    }

    Ok(decimal)
}

/// Fonction principale qui lit un fichier de mesures GQS et calcule la moyenne
///
/// # Complexité
/// - Temps : O(n * m) où n est le nombre de lignes et m la longueur moyenne d'une ligne
/// - Espace : O(m) pour le buffer de lecture d'une ligne
///
/// # Retour
/// * `Result<()>` - Ok si tout s'est bien passé, Err en cas d'erreur
fn main() -> Result<()> {
    let file_path = "gqs";
    // Ouverture du fichier - O(1)
    let file = File::open(file_path)
        .wrap_err_with(|| format!("Impossible d'ouvrir le fichier '{}'", file_path))?;
    let reader = BufReader::new(file);

    // Lecture et traitement de toutes les lignes du fichier
    // O(n * m) où n = nombre de lignes, m = longueur moyenne d'une ligne
    let (sum, count) = reader
        .lines()
        .map(|line| {
            // Lecture d'une ligne - O(m)
            let line = line.wrap_err("Erreur de lecture d'une ligne")?;
            // Conversion GQS -> décimal - O(m)
            gqs_to_decimal(&line)
        })
        // Accumulation de la somme et du compteur - O(n)
        .try_fold((0, 0), |(sum, count), decimal| {
            Ok::<(i32, i32), eyre::Report>((sum + decimal?, count + 1))
        })?;

    // Calcul et affichage de la moyenne - O(1)
    if count > 0 {
        let average = sum as f64 / count as f64;
        println!("{}", average);
    } else {
        println!("Aucune mesure trouvée dans le fichier.");
    }

    Ok(())
}

// Module de tests unitaires
#[cfg(test)]
mod tests {
    use super::*;
    use eyre::Result;

    /// Test de la conversion GQS vers décimal pour différents cas
    /// Complexité : O(1) pour chaque assertion (chaînes courtes de longueur constante)
    #[test]
    fn test_gqs_to_decimal() -> Result<()> {
        // Tests pour la conversion de symboles individuels
        assert_eq!(gqs_to_decimal("☃")?, -2);
        assert_eq!(gqs_to_decimal("❄")?, -1);
        assert_eq!(gqs_to_decimal("0")?, 0);
        assert_eq!(gqs_to_decimal("*")?, 1);
        assert_eq!(gqs_to_decimal("✦")?, 2);

        // Tests pour des chaînes de plusieurs symboles
        assert_eq!(gqs_to_decimal("✦0")?, 10); // 2*5 + 0*1 = 10
        assert_eq!(gqs_to_decimal("*")?, 1);
        assert_eq!(gqs_to_decimal("❄")?, -1);
        assert_eq!(gqs_to_decimal("☃")?, -2);
        assert_eq!(gqs_to_decimal("✦**")?, 56); // 2*25 + 1*5 + 1*1 = 56
        assert_eq!(gqs_to_decimal("✦*0❄")?, 274); // 2*125 + 1*25 + 0*5 + (-1)*1 = 274

        // Test pour une chaîne vide (si tu veux gérer ce cas)
        assert_eq!(gqs_to_decimal("")?, 0);

        Ok(())
    }

    /// Test de gestion d'erreur pour un symbole invalide
    /// Complexité : O(1)
    #[test]
    fn test_gqs_to_decimal_invalid() {
        // Test pour un symbole invalide
        let result = gqs_to_decimal("A");
        assert!(result.is_err());
        assert_eq!(
            result.unwrap_err().to_string(),
            "Symbole GQS invalide : 'A'"
        );
    }

    /// Test du calcul de moyenne avec des données simulées
    /// Complexité : O(n) où n est le nombre d'éléments dans test_data
    #[test]
    fn test_calculate_average() -> Result<()> {
        // Simulation d'un fichier avec les 5 mesures de l'exemple
        let test_data = ["✦0", "*", "❄", "☃", "✦**"];
        // Conversion et sommation - O(n * m) où m est la longueur moyenne
        let sum: i32 = test_data.iter().map(|s| gqs_to_decimal(s).unwrap()).sum();
        let count = test_data.len() as f64;
        let average = sum as f64 / count;
        assert_eq!(average, 12.8);

        Ok(())
    }

    /// Test du comportement avec un fichier vide
    /// Complexité : O(1) car le vecteur est vide
    #[test]
    fn test_empty_file() -> Result<()> {
        // Simulation d'un fichier vide
        let test_data = Vec::<&str>::new();
        let sum: i32 = test_data
            .iter()
            .map(|s| gqs_to_decimal(s).unwrap_or(0))
            .sum();
        let count = test_data.len() as f64;
        let average = if count > 0.0 { sum as f64 / count } else { 0.0 };
        assert_eq!(average, 0.0);

        Ok(())
    }
}
