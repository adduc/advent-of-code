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

	thresholds := map[string]int{
		"red":   12,
		"green": 13,
		"blue":  14,
	}

	sum := 0

outer:
	for {
		line, err := reader.ReadString('\n')
		if err != nil {
			break
		}

		// Parse out game ID, which may be multiple digits
		var gameID int
		fmt.Sscanf(line, "Game %d:", &gameID)

		// Parse out each color listed
		colors := map[string]int{
			"red":   0,
			"green": 0,
			"blue":  0,
		}

		// using regex, we can parse out the colors
		regex := regexp.MustCompile(`\d+ (red|green|blue)`)
		matches := regex.FindAllStringSubmatch(line, -1)

		// loop through matches and add to color count
		for _, match := range matches {
			count := 0
			color := ""
			fmt.Sscanf(match[0], "%d %s", &count, &color)

			colors[color] = max(colors[color], count)
		}

		// if colors are less than or equal to threshold, add to sum
		for color, count := range colors {
			if count > thresholds[color] {
				continue outer
			}
		}

		sum += gameID
	}

	// print sum
	fmt.Println(sum)
}
