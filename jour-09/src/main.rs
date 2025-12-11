use eyre::{Context, Result};
use std::f64::consts::PI;
use std::fs::File;
use std::io::{BufRead, BufReader};

/// Reads and parses CSV data from file, returning sorted coordinate tuples
/// Format: id,x_coordinate,y_coordinate (one per line)
/// Returns Vec sorted by the first field (id)
fn parse_and_sort(file_path: &str) -> Result<Vec<(i32, f64, f64)>> {
    // Open the file and wrap any error with context
    let file = File::open(file_path).wrap_err("Failed to open file")?;
    let reader = BufReader::new(file);

    // Parse each line into a tuple of (id, x, y)
    let mut data: Vec<(i32, f64, f64)> = reader
        .lines()
        .map(|line| {
            // Read the line, propagating any I/O errors
            let line = line.wrap_err("Failed to read line")?;
            let mut parts = line.split(',');

            Ok((
                // Parse the id field (first column)
                parts
                    .next()
                    .ok_or(eyre::eyre!("Missing id field"))?
                    .parse()?,
                // Parse the x coordinate (second column)
                parts
                    .next()
                    .ok_or(eyre::eyre!("Missing x field"))?
                    .parse()?,
                // Parse the y coordinate (third column)
                parts
                    .next()
                    .ok_or(eyre::eyre!("Missing y field"))?
                    .parse()?,
            ))
        })
        .collect::<Result<Vec<_>>>()?;

    // Sort entries by id to ensure chronological/sequential order
    data.sort_by_key(|k| k.0);

    Ok(data)
}

/// Converts Web Mercator projection (x,y in meters) to WGS84 lat/lon (degrees)
/// Uses inverse Mercator projection formulas
fn convert_to_wgs84(x_m: f64, y_m: f64) -> (f64, f64) {
    // WGS84 Earth equatorial radius in meters
    let r = 6378137.0;

    // Convert x (meters) to longitude (degrees)
    // Formula: lon = (x / r) * (180 / π)
    let lon_deg = (x_m / r) * 180.0 / PI;

    // Convert y (meters) to latitude (degrees) using inverse Mercator projection
    // Formula: lat = (2 * arctan(e^(y/r)) - π/2) * (180 / π)
    let lat_deg = (2.0 * (y_m / r).exp().atan() - PI / 2.0) * 180.0 / PI;

    (lon_deg, lat_deg)
}

/// Calculates great-circle distance between two points on Earth using Haversine formula
/// Input: lat/lon in degrees, Output: distance in kilometers
fn haversine_distance(lat1: f64, lon1: f64, lat2: f64, lon2: f64) -> f64 {
    // Convert all coordinates from degrees to radians
    let lat1_rad = lat1.to_radians();
    let lon1_rad = lon1.to_radians();
    let lat2_rad = lat2.to_radians();
    let lon2_rad = lon2.to_radians();

    // Calculate differences in coordinates
    let dlat = lat2_rad - lat1_rad;
    let dlon = lon2_rad - lon1_rad;

    // Haversine formula: a = sin²(Δlat/2) + cos(lat1) * cos(lat2) * sin²(Δlon/2)
    let a =
        (dlat / 2.0).sin().powi(2) + lat1_rad.cos() * lat2_rad.cos() * (dlon / 2.0).sin().powi(2);

    // Calculate the angular distance: c = 2 * arctan2(√a, √(1-a))
    let c = 2.0 * a.sqrt().atan2((1.0 - a).sqrt());

    // Multiply by Earth's mean radius (6371 km) to get distance in kilometers
    6371.0 * c
}

fn main() -> Result<()> {
    let file_path = "trace.txt";

    // Parse the trace file and sort points by id
    let sorted_data = parse_and_sort(file_path)?;

    // Get the first point (earliest in sequence)
    let first_point = sorted_data
        .first()
        .ok_or(eyre::eyre!("No data points found"))?;

    // Get the last point (latest in sequence)
    let last_point = sorted_data
        .last()
        .ok_or(eyre::eyre!("No data points found"))?;

    // Convert points from Web Mercator to WGS84 (lon, lat)
    let (lon1, lat1) = convert_to_wgs84(first_point.1, first_point.2);
    let (lon2, lat2) = convert_to_wgs84(last_point.1, last_point.2);

    // Calculate the great-circle distance between the two points
    let distance_km = haversine_distance(lat1, lon1, lat2, lon2);

    println!(
        "Distance between the first and last point: {:.2} km",
        distance_km
    );

    Ok(())
}

#[cfg(test)]
mod tests {
    use super::*;
    use std::io::Write;
    use tempfile::NamedTempFile;

    /// Tests that parse_and_sort correctly reads CSV data and sorts by id
    ///
    /// Creates a temporary file with three data points in unsorted order (3, 1, 2)
    /// and verifies that they are sorted correctly (1, 2, 3) and that the
    /// coordinate values are preserved accurately.
    #[test]
    fn test_parse_and_sort() -> Result<()> {
        // Create a temporary file with unsorted data
        let mut file = NamedTempFile::new()?;

        writeln!(file, "3,1000.0,2000.0")?; // Third point
        writeln!(file, "1,3000.0,4000.0")?; // First point
        writeln!(file, "2,2000.0,3000.0")?; // Second point

        let path = file.path().to_str().unwrap();
        let data = parse_and_sort(path)?;

        // Verify correct sorting by id
        assert_eq!(data[0].0, 1);
        assert_eq!(data[1].0, 2);
        assert_eq!(data[2].0, 3);

        // Verify that coordinates are correctly associated with id=1
        assert_eq!(data[0].1, 3000.0); // x coordinate
        assert_eq!(data[0].2, 4000.0); // y coordinate

        Ok(())
    }

    /// Tests Web Mercator to WGS84 coordinate conversion
    ///
    /// Verifies two scenarios:
    /// 1. Typical case: arbitrary coordinates produce valid lat/lon ranges
    /// 2. Origin case: (0,0) in Web Mercator maps to (0°, 0°) in WGS84
    ///    (equator and prime meridian intersection)
    #[test]
    fn test_convert_to_wgs84() {
        // Test typical conversion with positive coordinates
        let (lon, lat) = convert_to_wgs84(1000.0, 2000.0);
        assert!(lon > 0.0); // Should be positive longitude
        assert!(lat > -90.0 && lat < 90.0); // Valid latitude range

        // Test origin point: (0, 0) should map to equator/prime meridian
        let (lon, lat) = convert_to_wgs84(0.0, 0.0);
        assert!(lon.abs() < 1e-10); // Longitude ≈ 0° (prime meridian)
        assert!(lat.abs() < 1e-10); // Latitude ≈ 0° (equator)
    }

    /// Tests Haversine distance calculation between geographic points
    ///
    /// Verifies two scenarios:
    /// 1. Zero distance: same point should return distance ≈ 0
    /// 2. Known distance: Paris to New York is approximately 5850 km
    ///    (allows 50 km margin for Earth radius approximation)
    #[test]
    fn test_haversine_distance() {
        // Test zero distance (Paris to Paris)
        let distance = haversine_distance(48.8566, 2.3522, 48.8566, 2.3522);
        assert!(distance < 1e-6); // Should be essentially zero

        // Test known distance: Paris (48.8566°N, 2.3522°E) to New York (40.7128°N, 74.0060°W)
        let distance = haversine_distance(48.8566, 2.3522, 40.7128, -74.0060);
        assert!((distance - 5850.0).abs() < 50.0); // Should be ~5850 km ±50 km
    }

    /// Tests handling of empty files
    ///
    /// Creates an empty temporary file and verifies that parse_and_sort
    /// returns an empty vector rather than failing or panicking.
    #[test]
    fn test_parse_and_sort_empty_file() -> Result<()> {
        // Create an empty temporary file
        let file = NamedTempFile::new()?;
        let path = file.path().to_str().unwrap();
        let data = parse_and_sort(path)?;

        // Should return empty vector, not error
        assert!(data.is_empty());

        Ok(())
    }

    /// Tests extreme coordinate conversion at the edge of the projection
    ///
    /// When x_m equals Earth's radius times π (r × π), the resulting
    /// longitude should be exactly 180° (the antimeridian, opposite of
    /// the prime meridian). This tests the boundary behavior of the
    /// Web Mercator to WGS84 conversion.
    #[test]
    fn test_convert_to_wgs84_extreme() {
        // x = r × π should map to longitude = 180° (antimeridian)
        let (lon, _) = convert_to_wgs84(6_378_137.0 * PI, 0.0);

        // Verify longitude is at the antimeridian with floating-point precision
        assert!((lon - 180.0).abs() < 1e-10);
    }
}
