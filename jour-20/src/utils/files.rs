use eyre::{Context, Result};
use std::path::{Path, PathBuf};
use walkdir::WalkDir;

/// Represents a file system node (either a file or directory)
#[derive(Debug, Clone)]
pub enum FileNode {
    /// A file with its path
    File(PathBuf),
    /// A directory with its path and children nodes
    Directory {
        path: PathBuf,
        children: Vec<FileNode>,
    },
}

/// Builds a tree structure of files and directories from a given path
/// 
/// # Arguments
/// * `path` - The root path to start building the tree from
/// * `recursive` - Whether to recursively traverse subdirectories
/// 
/// # Returns
/// * A vector of FileNodes representing the directory structure
pub fn build_file_tree(path: &Path, recursive: bool) -> Result<Vec<FileNode>> {
    let mut tree = Vec::new();

    for entry in WalkDir::new(path).max_depth(if recursive { usize::MAX } else { 1 }) {
        let entry = entry
            .with_context(|| format!("Unable to read entry in {}", path.display()))?;
        let entry_path = entry.path().to_path_buf();

        // Skip the root directory itself
        if entry.depth() == 0 {
            continue;
        }

        if entry.file_type().is_dir() {
            if recursive && entry.depth() > 0 {
                // Recursive mode: build the tree of subdirectories
                let children = build_file_tree(&entry_path, recursive).with_context(|| {
                    format!("Error reading {}", entry_path.display())
                })?;
                tree.push(FileNode::Directory {
                    path: entry_path,
                    children,
                });
            } else if !recursive {
                // Non-recursive mode: just add the directory without children
                tree.push(FileNode::Directory {
                    path: entry_path,
                    children: Vec::new(),
                });
            }
        } else {
            // It's a file
            tree.push(FileNode::File(entry_path));
        }
    }

    Ok(tree)
}

/// Gets the size of a file in bytes
/// 
/// # Arguments
/// * `path` - Path to the file
/// 
/// # Returns
/// * The file size in bytes
pub fn get_file_size(path: &Path) -> Result<u64> {
    let metadata = std::fs::metadata(path)
        .with_context(|| format!("Unable to read metadata for {}", path.display()))?;
    Ok(metadata.len())
}
