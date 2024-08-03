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

		// iterate through each character in the line, pulling out th
		// first number we find, and the last number we find
		for _, c := range line {
			if c >= '0' && c <= '9' {
				last = int(c - '0')
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
