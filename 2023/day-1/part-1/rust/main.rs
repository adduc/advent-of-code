use std::io;

fn main() {
    let stdin = io::stdin();
    let mut buffer = String::new();
    let mut sum = 0;

    // Read each line
    while let Ok(n) = stdin.read_line(&mut buffer) {
        if n == 0 {
            break;
        }

        let mut first = 0;
        let mut last = 0;
        let mut first_found = false;

        // iterate through the string and pull out the first and last
        // numeric digits
        for c in buffer.chars() {
            if !c.is_numeric() {
                continue;
            }
        
            last = c.to_digit(10).unwrap();
            
            if !first_found {
                first = last;
                first_found = true;
            }
        }

        // combine the first and last number and add it to the sum
        sum += first * 10 + last;

        // Clear the buffer
        buffer.clear();
    }

    // Print the sum
    println!("{}", sum);
}
