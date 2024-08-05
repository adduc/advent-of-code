///usr/bin/true; exec /usr/bin/env go run "$0" "$@"

package main

import (
	"bufio"
	"fmt"
	"os"
	"regexp"
)

func main() {
	// using bufio to read input, print result
	reader := bufio.NewReader(os.Stdin)

	red_threshold := 12
	green_threshold := 13
	blue_threshold := 14

	sum := 0

	for {
		line, err := reader.ReadString('\n')
		if err != nil {
			break
		}

		// Parse out game ID, which may be multiple digits
		var gameID int
		fmt.Sscanf(line, "Game %d:", &gameID)

		// Parse out each color listed
		var red, green, blue int

		// using regex, we can parse out the colors
		regex := regexp.MustCompile(`\d+ (red|green|blue)`)
		matches := regex.FindAllStringSubmatch(line, -1)

		// loop through matches and add to color count
		for _, match := range matches {
			count := 0
			color := ""
			fmt.Sscanf(match[0], "%d %s", &count, &color)

			switch color {
			case "red":
				red = max(red, count)
			case "green":
				green = max(green, count)
			case "blue":
				blue = max(blue, count)
			}
		}

		// if colors are less than or equal to threshold, add to sum
		if red <= red_threshold && green <= green_threshold && blue <= blue_threshold {
			sum += gameID
		}
	}

	// print sum
	fmt.Println(sum)
}
