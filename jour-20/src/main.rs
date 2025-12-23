use clap::Parser;
use eyre::Error;

pub mod utils;

use crate::utils::cli;
use crate::utils::executor;

/// Main entry point of the application
/// Parses CLI arguments and executes the appropriate command
fn main() -> Result<(), Error> {
    // Parse command-line arguments
    let cli = cli::Cli::parse();

    let command = &cli.command;

    // Extract the path from the command
    let path = cli.get_path();

    // Execute the command with the specified path
    executor::execute(path, command)?;

    Ok(())
}
