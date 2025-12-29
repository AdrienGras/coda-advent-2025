use crossterm::{
    cursor::MoveTo,
    execute,
    style::{Color, Print, ResetColor, SetForegroundColor},
    terminal::{Clear, ClearType, disable_raw_mode, enable_raw_mode, size},
};
use rand::Rng;
use std::io::stdout;
use std::thread;
use std::time::Duration;

fn main() {
    enable_raw_mode().unwrap();
    let mut stdout = stdout();
    let (width, _) = size().unwrap(); // Récupère la largeur du terminal

    // Position de départ (centré)
    let start_col = (width as usize).saturating_sub(20) / 2;
    let height = 10; // Hauteur du sapin

    for i in 0..20 {
        // Nombre d'itérations pour l'animation
        execute!(stdout, Clear(ClearType::All)).unwrap();

        // Dessiner le sapin et les décorations
        for row in 0..height {
            let mut line = String::new();
            // Espaces avant les épines
            for _ in 0..(height - row - 1) {
                line.push(' ');
            }

            // Épines et décorations
            for _ in 0..(2 * row + 1) {
                let mut rng = rand::rng();
                let random: u8 = rng.random_range(0..15);

                if random == 0 {
                    line.push_str(&format!(
                        "{}★{}",
                        SetForegroundColor(Color::Red),
                        ResetColor
                    ));
                } else if random == 1 {
                    line.push_str(&format!(
                        "{}☆{}",
                        SetForegroundColor(Color::Yellow),
                        ResetColor
                    ));
                } else if random == 2 {
                    line.push_str(&format!(
                        "{}◈{}",
                        SetForegroundColor(Color::Blue),
                        ResetColor
                    ));
                } else {
                    line.push_str(&format!(
                        "{}▲{}",
                        SetForegroundColor(Color::Green),
                        ResetColor
                    ));
                }
            }

            // Affiche la ligne à la bonne position
            execute!(stdout, MoveTo(start_col as u16, row as u16), Print(line)).unwrap();
        }

        // Tronc marron (centré)
        let trunk_col = start_col + height - 2;
        for i in 0..2 {
            execute!(
                stdout,
                MoveTo(trunk_col as u16, (height + i) as u16),
                SetForegroundColor(Color::DarkYellow),
                Print("││"),
                ResetColor
            )
            .unwrap();
        }

        // Guirlandes clignotantes (magenta)
        if i % 2 == 0 {
            for row in 0..height {
                let mut line = String::new();
                for _ in 0..(height - row - 1) {
                    line.push(' ');
                }
                for _ in 0..(2 * row + 1) {
                    line.push_str(&format!(
                        "{}▲{}",
                        SetForegroundColor(Color::Green),
                        ResetColor
                    ));
                }
                execute!(stdout, MoveTo(start_col as u16, row as u16), Print(line)).unwrap();
            }
        }

        thread::sleep(Duration::from_millis(500));
    }

    disable_raw_mode().unwrap();
}
