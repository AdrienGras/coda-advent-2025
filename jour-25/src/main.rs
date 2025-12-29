use eyre::Result;
use std::collections::HashMap;
use std::fs;

/// Parse une ligne de données et retourne le nombre d'enfants mécontents par pays.
fn parse_unhappy_children(data: &str) -> HashMap<String, usize> {
    let mut unhappy_counts: HashMap<String, usize> = HashMap::new();

    data.split('|')
        .filter(|record| is_valid_unhappy_record(record))
        .for_each(|record| {
            let parts: Vec<&str> = record.split('-').collect();

            let country = parts[0].to_string();

            *unhappy_counts.entry(country).or_insert(0) += 1;
        });

    unhappy_counts
}

/// Vérifie si un enregistrement est valide et correspond à un enfant mécontent.
fn is_valid_unhappy_record(record: &str) -> bool {
    let parts: Vec<&str> = record.split('-').collect();

    parts.len() == 4
        && !parts.iter().any(|p| p.is_empty())
        && parts[2] == "unhappy"
        && parts[3].parse::<u32>().is_ok()
}

fn main() -> Result<()> {
    let data = fs::read_to_string("input.txt")?;

    let unhappy_counts = parse_unhappy_children(&data);

    println!("=== Rapport des Enfants Mécontents ===\n");

    let mut sorted_countries: Vec<_> = unhappy_counts.iter().collect();
    sorted_countries.sort_by(|a, b| b.1.cmp(a.1));

    for (country, count) in sorted_countries {
        println!("{} : {} mécontents", country, count);
    }

    let total: usize = unhappy_counts.values().sum();
    println!("\nTotal global : {} enfants mécontents", total);

    Ok(())
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_is_valid_unhappy_record() {
        // Cas valides
        assert!(is_valid_unhappy_record("France-Lucie-unhappy-7"));
        assert!(is_valid_unhappy_record("Brazil-Antonio-unhappy-10"));

        // Cas invalides
        assert!(!is_valid_unhappy_record("France--happy-7")); // prénom vide
        assert!(!is_valid_unhappy_record("Italy-Mario-12")); // champ satisfaction manquant
        assert!(!is_valid_unhappy_record("??-??-happy-?")); // caractères invalides
        assert!(!is_valid_unhappy_record("Belgium-Laura-happiness-9")); // satisfaction invalide
        assert!(!is_valid_unhappy_record("USA-Mike-neutral-")); // âge vide
        assert!(!is_valid_unhappy_record("Spain-Pedro-unhappy-")); // âge vide
        assert!(!is_valid_unhappy_record("Canada-Sophie-happy-6")); // satisfaction happy
        assert!(!is_valid_unhappy_record("Poland-Anna-neutral-8")); // satisfaction neutral
    }

    #[test]
    fn test_parse_unhappy_children() {
        let data = "France-Lucie-unhappy-7|Brazil-Antonio-happy-10|Japan-Hiro-unhappy-11|??-??-happy-?|Germany-Lena-unhappy-9|Spain--neutral-8|USA-Mike-happiness-12";
        let result = parse_unhappy_children(data);
        assert_eq!(result.get("France"), Some(&1));
        assert_eq!(result.get("Japan"), Some(&1));
        assert_eq!(result.get("Germany"), Some(&1));
        assert_eq!(result.len(), 3);
    }

    #[test]
    fn test_empty_input() {
        let result = parse_unhappy_children("");
        assert!(result.is_empty());
    }

    #[test]
    fn test_no_unhappy_children() {
        let data = "France-Lucie-happy-7|Brazil-Antonio-neutral-10";
        let result = parse_unhappy_children(data);
        assert!(result.is_empty());
    }
}
