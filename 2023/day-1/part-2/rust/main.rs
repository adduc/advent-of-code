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
		let mut character_buffer = String::new();

        // iterate through the string and pull out the first and last
        // numeric digits
        for c in buffer.chars() {
            if c.is_numeric() {
                last = c.to_digit(10).unwrap();

                if !first_found {
                    first = last;
                    first_found = true;
                }

                // reset the buffer
                character_buffer = String::new();

                continue;
            }

            // append to the character buffer
            character_buffer.push_str(&c.to_string());

            // find 
            let length = character_buffer.len();
            for i in 0..length {
                match &character_buffer[i..] {
                    "one" => last = 1,
                    "two" => last = 2,
                    "three" => last = 3,
                    "four" => last = 4,
                    "five" => last = 5,
                    "six" => last = 6,
                    "seven" => last = 7,
                    "eight" => last = 8,
                    "nine" => last = 9,
                    _ => continue,
                }
    
                if !first_found {
                    first = last;
                    first_found = true;
                }
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
