use regex::Regex;
use std::collections::HashMap;
use std::io;

fn main() {
    // Prepare to read from stdin
    let stdin = io::stdin();
    let mut buffer = String::new();

    // Preparse running sum
    let mut sum = 0;

    // Create a regex to find counts of colors
    let re_colors = Regex::new(r"(\d+) (\w+)").unwrap();

    // Read each line
    while let Ok(n) = stdin.read_line(&mut buffer) {
        // if we have reached the end of the file, break
        if n == 0 {
            break;
        }

        // Parse the colors and counts
        let mut counts: HashMap<&str, i32> = HashMap::new();
        for cap in re_colors.captures_iter(&buffer) {
            let count = cap.get(1).unwrap().as_str().parse::<i32>().unwrap();
            let color = cap.get(2).unwrap().as_str();

            // set counts[color] to max of count and current value
            let current = counts.entry(color).or_insert(0);
            *current = std::cmp::max(*current, count);
        }
        
        // take product of counts and add to sum
        sum += counts.values().product::<i32>();

        // Clear the buffer
        buffer.clear();
    }

    // Print the sum
    println!("{}", sum);
}
