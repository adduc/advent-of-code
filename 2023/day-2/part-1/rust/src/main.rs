use regex::Regex;
use std::collections::HashMap;
use std::io;

fn main() {
    // Prepare to read from stdin
    let stdin = io::stdin();
    let mut buffer = String::new();

    // Preparse running sum
    let mut sum = 0;

    // create map of thresholds
    let thresholds = HashMap::from([("red", 12), ("green", 13), ("blue", 14)]);

    // Create a regex to find counts of colors
    let re_game = Regex::new(r"Game (\d+):").unwrap();
    let re_colors = Regex::new(r"(\d+) (\w+)").unwrap();

    // Read each line
    'outer: while let Ok(n) = stdin.read_line(&mut buffer) {
        // if we have reached the end of the file, break
        if n == 0 {
            break;
        }

        // Extract game ID as a number
        let game_id = re_game.captures(&buffer).unwrap().get(1).unwrap().as_str();

        // Parse the colors and counts
        let mut counts = HashMap::new();
        for cap in re_colors.captures_iter(&buffer) {
            let count = cap.get(1).unwrap().as_str().parse::<i32>().unwrap();
            let color = cap.get(2).unwrap().as_str();

            // set counts[color] to max of count and current value
            let current = counts.entry(color).or_insert(0);
            *current = std::cmp::max(*current, count);
        }

        // Check if the counts exceed the thresholds
        for (color, count) in counts {
            if count > *thresholds.get(color).unwrap() {
                // If so, skip this game
                buffer.clear();
                continue 'outer;
            }
        }
        
        // add game ID to sum
        sum += game_id.parse::<i32>().unwrap();

        // Clear the buffer
        buffer.clear();
    }

    // Print the sum
    println!("{}", sum);
}
