///usr/bin/true; exec /usr/bin/env go run "$0" "$@"

package main

import (
	"fmt"
)

func main() {

	var line string
	sum := 0

	// loop through each line from stdin
	for {
		_, err := fmt.Scanln(&line)
		if err != nil {
			break
		}

		first := 0
		last := 0
		first_found := false
		character_buffer := ""

		for _, c := range line {
			// Track numbers as we find them
			if c >= '0' && c <= '9' {
				last = int(c - '0')
				if !first_found {
					first = last
					first_found = true
				}

				character_buffer = ""
				continue
			}

			// Look at all of the characters since the last number we found
			// If we find a numeric word, track it
			character_buffer += string(c)
			length := len(character_buffer)

			for i := 0; i < length; i++ {
				switch character_buffer[i:] {
				case "one":
					last = 1
				case "two":
					last = 2
				case "three":
					last = 3
				case "four":
					last = 4
				case "five":
					last = 5
				case "six":
					last = 6
				case "seven":
					last = 7
				case "eight":
					last = 8
				case "nine":
					last = 9
				default:
					continue
				}

				if !first_found {
					first = last
					first_found = true
				}
			}
		}

		// add the first number to the last number, and add it to the sum
		sum += first*10 + last
	}

	// print sum
	fmt.Println(sum)
}
