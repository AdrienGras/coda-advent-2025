use clap::{Parser, Subcommand};

/// Command-line interface structure
#[derive(Parser)]
#[command(
    version,
    about = "List toys in different formats",
    long_about = "A command to list toys in different formats."
)]
pub struct Cli {
    #[command(subcommand)]
    pub command: Commands,
}

/// Available commands for displaying file information
#[derive(Subcommand)]
pub enum Commands {
    /// Displays elements in a detailed array format
    Normal {
        #[arg(help = "Path to the toys directory")]
        path: String,
    },
    /// Displays element in a compact line format
    Compact {
        #[arg(help = "Path to the toys directory")]
        path: String,
    },
    /// Displays elements in a tree structure
    Tree {
        #[arg(help = "Path to the toys directory")]
        path: String,
    },
}

impl Cli {
    /// Extracts the path from whichever command was used
    pub fn get_path(&self) -> &String {
        match &self.command {
            Commands::Normal { path } | Commands::Compact { path } | Commands::Tree { path } => {
                path
            }
        }
    }
}
