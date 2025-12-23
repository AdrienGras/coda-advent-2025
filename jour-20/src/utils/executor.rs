use eyre::Error;

use crate::utils::cli::Commands;
use crate::utils::{display, files};

/// Executes the appropriate display command based on user input
///
/// # Arguments
/// * `path` - The path to the directory to display
/// * `command` - The display command to execute (Normal, Compact, or Tree)
///
/// # Returns
/// * `Ok(())` if successful, or an error if file operations fail
pub fn execute(path: &String, command: &super::cli::Commands) -> Result<(), Error> {
    // Only Tree command needs recursive directory traversal
    let is_recursive = matches!(command, Commands::Tree { .. });

    // Build the file tree from the specified path
    let file_node = files::build_file_tree(std::path::Path::new(path), is_recursive)?;

    // Execute the appropriate display function based on the command
    match command {
        Commands::Normal { .. } => {
            display::display_normal(&file_node);
        }
        Commands::Compact { .. } => {
            display::display_compact(&file_node);
        }
        Commands::Tree { .. } => {
            display::display_tree(&file_node, "");
        }
    }
    Ok(())
}
