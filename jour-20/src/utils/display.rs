use comfy_table::{Attribute, Cell, Color, Table, presets::NOTHING};
use rand::Rng;
use std::path::Path;

use crate::utils::files::{self, get_file_size};

/// Statistics structure to track file and directory counts
#[derive(Debug, Default)]
struct Stats {
    files: usize,
    directories: usize,
    total_size: u64,
    total_weight: u32,
}

/// Colorizes a string in blue (for files) using ANSI escape codes
fn colorize_file(s: &str) -> String {
    format!("\x1b[34m{}\x1b[0m", s)
}

/// Colorizes a string in bold yellow (for directories) using ANSI escape codes
fn colorize_dir(s: &str) -> String {
    format!("\x1b[1;33m{}\x1b[0m", s)
}

/// Extracts the filename or directory name from a path
fn get_node_name(path: &Path) -> String {
    path.file_name()
        .unwrap_or_default()
        .to_string_lossy()
        .to_string()
}

/// Generates a random weight between 1 and 1000 grams
fn generate_weight() -> String {
    let weight: u32 = rand::rng().random_range(1..=1000);
    format!("{} g", weight)
}

/// Generates random magic sparkles (1 or 3 sparkle emojis)
fn generate_magic() -> String {
    let magic: u32 = rand::rng().random_range(1..=3);
    "✨".repeat(magic as usize)
}

/// Sorts file nodes alphabetically (directories first, then files)
fn sort_nodes(nodes: &mut [files::FileNode]) {
    nodes.sort_by(|a, b| {
        let (name_a, is_dir_a) = match a {
            files::FileNode::Directory { path, .. } => (get_node_name(path), true),
            files::FileNode::File(path) => (get_node_name(path), false),
        };
        let (name_b, is_dir_b) = match b {
            files::FileNode::Directory { path, .. } => (get_node_name(path), true),
            files::FileNode::File(path) => (get_node_name(path), false),
        };

        // Directories before files
        match (is_dir_a, is_dir_b) {
            (true, false) => std::cmp::Ordering::Less,
            (false, true) => std::cmp::Ordering::Greater,
            _ => name_a.to_lowercase().cmp(&name_b.to_lowercase()),
        }
    });
}

/// Recursively calculates statistics for a collection of file nodes
fn calculate_stats(nodes: &[files::FileNode]) -> Stats {
    let mut stats = Stats::default();

    for node in nodes {
        match node {
            files::FileNode::File(path) => {
                stats.files += 1;
                if let Ok(size) = get_file_size(path) {
                    stats.total_size += size;
                }
                // Generate weight for statistics
                let weight: u32 = rand::rng().random_range(1..1000);
                stats.total_weight += weight;
            }
            files::FileNode::Directory { children, .. } => {
                stats.directories += 1;
                let child_stats = calculate_stats(children);
                stats.files += child_stats.files;
                stats.directories += child_stats.directories;
                stats.total_size += child_stats.total_size;
                stats.total_weight += child_stats.total_weight;
            }
        }
    }

    stats
}

/// Displays file nodes in a detailed table format
/// Shows name, size, weight, and magic attributes in a formatted table
pub fn display_normal(file_nodes: &[files::FileNode]) {
    let mut sorted_nodes = file_nodes.to_vec();
    sort_nodes(&mut sorted_nodes);

    let mut table = Table::new();
    table.load_preset(NOTHING);

    // Set up table headers with colors
    table.set_header(vec![
        Cell::new("Name")
            .fg(Color::Blue)
            .add_attribute(Attribute::Bold)
            .add_attribute(Attribute::Underlined),
        Cell::new("Size")
            .fg(Color::Yellow)
            .add_attribute(Attribute::Bold)
            .add_attribute(Attribute::Underlined),
        Cell::new("Weight")
            .fg(Color::White)
            .add_attribute(Attribute::Bold)
            .add_attribute(Attribute::Underlined),
        Cell::new("Magic")
            .fg(Color::Green)
            .add_attribute(Attribute::Bold)
            .add_attribute(Attribute::Underlined),
    ]);

    // Populate table rows
    for file_node in &sorted_nodes {
        match file_node {
            files::FileNode::File(path) => {
                let file_name = get_node_name(path);
                let file_size = match get_file_size(path) {
                    Ok(size) => format!("{} cm", size),
                    Err(_) => "<error>".to_string(),
                };
                let file_weight = generate_weight();
                let magic = generate_magic();

                table.add_row(vec![
                    Cell::new(format!("📄 {}", file_name)).fg(Color::Blue),
                    Cell::new(file_size).fg(Color::Yellow),
                    Cell::new(file_weight).fg(Color::White),
                    Cell::new(magic).fg(Color::Green),
                ]);
            }
            files::FileNode::Directory { path, .. } => {
                let dir_name = get_node_name(path);
                table.add_row(vec![
                    Cell::new(format!("📁 {}", dir_name))
                        .fg(Color::Yellow)
                        .add_attribute(Attribute::Bold),
                    Cell::new("-").fg(Color::Yellow),
                    Cell::new("-").fg(Color::White),
                    Cell::new("-").fg(Color::Green),
                ]);
            }
        }
    }

    println!("{}", table);

    // Display statistics
    let stats = calculate_stats(&sorted_nodes);
    println!(
        "\n📊 Statistics: {} file(s), {} director(y|ies), total size: {} cm, total weight: {} g",
        stats.files, stats.directories, stats.total_size, stats.total_weight
    );
}

/// Displays file nodes in a compact, comma-separated format
/// Each element shows its emoji, name, and attributes on a single line
pub fn display_compact(file_nodes: &[files::FileNode]) {
    let mut sorted_nodes = file_nodes.to_vec();
    sort_nodes(&mut sorted_nodes);

    let mut result_arr = Vec::new();

    for file_node in &sorted_nodes {
        match file_node {
            files::FileNode::File(path) => {
                let file_name = get_node_name(path);
                let file_size = match get_file_size(path) {
                    Ok(size) => format!("{} cm", size),
                    Err(_) => "<error>".to_string(),
                };
                let file_weight = generate_weight();
                let magic = generate_magic();

                result_arr.push(format!(
                    "📄 {} ({}, {}, {})",
                    file_name, file_size, file_weight, magic
                ));
            }
            files::FileNode::Directory { path, .. } => {
                let dir_name = get_node_name(path);
                result_arr.push(format!("📁 {}", dir_name));
            }
        }
    }

    println!("{}", result_arr.join(", "));

    // Display statistics
    let stats = calculate_stats(&sorted_nodes);
    println!(
        "\n📊 Statistics: {} file(s), {} director(y|ies), total size: {} cm, total weight: {} g",
        stats.files, stats.directories, stats.total_size, stats.total_weight
    );
}

/// Displays file nodes in a tree structure
/// Uses box-drawing characters to show the hierarchy
pub fn display_tree(file_nodes: &[files::FileNode], prefix: &str) {
    display_tree_recursive(file_nodes, prefix, true);

    // Display statistics only at root level (when prefix is empty)
    if prefix.is_empty() {
        let stats = calculate_stats(file_nodes);
        println!(
            "\n📊 Statistics: {} file(s), {} director(y|ies), total size: {} cm, total weight: {} g",
            stats.files, stats.directories, stats.total_size, stats.total_weight
        );
    }
}

/// Recursive helper function to display the tree structure
/// Uses different branch characters for last items (└──) vs middle items (├──)
fn display_tree_recursive(file_nodes: &[files::FileNode], prefix: &str, _is_root: bool) {
    let mut sorted_nodes = file_nodes.to_vec();
    sort_nodes(&mut sorted_nodes);

    let len = sorted_nodes.len();

    for (index, file_node) in sorted_nodes.iter().enumerate() {
        let is_last = index == len - 1;
        let branch = if is_last { "└──" } else { "├──" };
        let extension = if is_last { "    " } else { "│   " };

        match file_node {
            files::FileNode::File(path) => {
                let file_name = get_node_name(path);
                let file_size = match get_file_size(path) {
                    Ok(size) => format!("{} cm", size),
                    Err(_) => "<error>".to_string(),
                };
                let file_weight = generate_weight();
                let magic = generate_magic();

                println!(
                    "{}{} 📄 {} ({}, {}, {})",
                    prefix,
                    branch,
                    colorize_file(&file_name),
                    file_size,
                    file_weight,
                    magic
                );
            }
            files::FileNode::Directory { path, children } => {
                let dir_name = get_node_name(path);
                println!("{}{} 📁 {}", prefix, branch, colorize_dir(&dir_name));
                display_tree_recursive(children, &format!("{}{}", prefix, extension), false);
            }
        }
    }
}
