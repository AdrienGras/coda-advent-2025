//! Advent of Code - Day 4: Calorie Counting
//!
//! This program reads a data file containing elf names and their calorie counts,
//! then identifies the top 3 elves carrying the most calories.

use std::cmp::Reverse;
use std::collections::BinaryHeap;
use std::fs;
use std::io::Error;

/// Generates the top 3 elves with the highest calorie counts from the input file content.
///
/// The input format is expected to be:
/// - Elf name on a line
/// - Followed by lines containing calorie values (one per line)
/// - Empty line separates different elves
///
/// # Arguments
///
/// * `content` - The full content of the data file as a String
///
/// # Returns
///
/// A vector of tuples containing (elf_name, total_calories) for the top 3 elves,
/// sorted in descending order by calories.
///
/// # Algorithm
///
/// Uses a min-heap (BinaryHeap with Reverse) to efficiently maintain only the top 3
/// elves while processing the file, ensuring O(n log 3) = O(n) time complexity.
fn generate_top3_from_file(content: String) -> Vec<(String, u32)> {
    // Current elf being processed
    let mut current_elf: String = String::new();
    // Running total of calories for the current elf
    let mut current_calories: u32 = 0;
    // Min-heap to maintain top 3 elves (using Reverse for min-heap behavior)
    let mut heap: BinaryHeap<Reverse<(u32, String)>> = BinaryHeap::new();

    for line in content.lines() {
        if line.is_empty() {
            // Empty line indicates end of current elf's data
            if !current_elf.is_empty() {
                // Insert current elf into the heap
                heap.push(Reverse((current_calories, current_elf.clone())));
                // Keep only top 3 by removing the smallest when size exceeds 3
                if heap.len() > 3 {
                    heap.pop();
                }
                current_elf.clear();
                current_calories = 0;
            }
        } else if current_elf.is_empty() {
            // First non-empty line after separator is the elf's name
            current_elf = line.to_string();
        } else {
            // Subsequent lines are calorie values to be summed
            current_calories += line.parse::<u32>().unwrap_or(0);
        }
    }

    // Process the last elf (file might not end with empty line)
    if !current_elf.is_empty() {
        heap.push(Reverse((current_calories, current_elf)));
        if heap.len() > 3 {
            heap.pop();
        }
    }

    // Extract the top 3 values from the heap (they come out in ascending order)
    let mut top3: Vec<(String, u32)> = Vec::new();
    while let Some(Reverse((calories, name))) = heap.pop() {
        top3.push((name, calories));
    }
    // Reverse to get descending order (highest calories first)
    top3.reverse();

    top3
}

/// Displays the top 3 elves and their calorie counts in a formatted, user-friendly way.
///
/// # Arguments
///
/// * `top3` - A slice of tuples containing (elf_name, calories) in descending order
///
/// # Output Format
///
/// - 🍪 Displays the top elf as "Elf of the Day"
/// - 🥈 Shows the second place elf
/// - 🥉 Shows the third place elf
/// - 🎁 Displays the combined total of all top 3 elves
fn present_top3(top3: &[(String, u32)]) {
    if !top3.is_empty() {
        println!(
            "🍪 Elf of the Day: {} with {} calories!",
            top3[0].0, top3[0].1
        );
        if top3.len() > 1 {
            println!("🥈 Then comes {} ({})", top3[1].0, top3[1].1);
        }
        if top3.len() > 2 {
            println!("🥉 And {} ({})", top3[2].0, top3[2].1);
        }
        let total: u32 = top3.iter().map(|&(_, calories)| calories).sum();
        println!("🎁 Combined snack power of Top 3: {} calories!", total);
    }
}

/// Main entry point of the program.
///
/// Reads the data file, processes the elf calorie data, and displays the top 3 results.
fn main() {
    let maybe_content: Result<String, Error> = fs::read_to_string("data.txt");

    let content: String = match maybe_content {
        Ok(c) => c,
        Err(e) => {
            eprintln!("Error reading data file: {}", e);
            return;
        }
    };

    let top3: Vec<(String, u32)> = generate_top3_from_file(content);

    if !top3.is_empty() {
        present_top3(&top3);
    }
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn test_generate_top3_from_file() {
        let maybe_content: Result<String, Error> = fs::read_to_string("unit_test_data.txt");

        let content: String = match maybe_content {
            Ok(c) => c,
            Err(e) => {
                eprintln!("Error reading data file: {}", e);
                return;
            }
        };

        let top3: Vec<(String, u32)> = generate_top3_from_file(content);
        let sum: u32 = top3.iter().map(|&(_, calories)| calories).sum::<u32>();

        assert_eq!(top3.len(), 3);
        assert_eq!(top3[0].0, "Nora");
        assert_eq!(top3[0].1, 24_000);
        assert_eq!(top3[1].0, "Marius");
        assert_eq!(top3[1].1, 11_000);
        assert_eq!(top3[2].0, "Tika");
        assert_eq!(top3[2].1, 10_000);
        assert_eq!(sum, 45_000);
    }
}
