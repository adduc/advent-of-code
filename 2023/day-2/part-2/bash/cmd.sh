#!/bin/bash
##
# Usage: cat input.txt | ./cmd.sh
##

SUM=0

while read line; do
  RED=0 GREEN=0 BLUE=0

  # parse the line
  GAME_ID=$(echo $line | cut -d' ' -f2 | cut -d':' -f1)

  # remove the game id
  colors=$(echo $line | cut -d' ' -f3-)

  # remove the semicolons and commas
  colors=$(echo $line | tr -d ';,')

  # loop through two words at a time
  set -- $colors
  while [ $# -gt 0 ]; do
    # get the color and the count
    COUNT=$1
    COLOR=$2
    
    # take the maximum of the count and the threshold
    case $COLOR in
      red) RED=$((RED > COUNT ? RED : COUNT));;
      green) GREEN=$((GREEN > COUNT ? GREEN : COUNT));;
      blue) BLUE=$((BLUE > COUNT ? BLUE : COUNT));;
    esac
    
    # shift the arguments
    shift 2
  done

  # add power of all colors to the sum
  POWER=$((RED * GREEN * BLUE))

  SUM=$((SUM + POWER))

  echo "Game ID: $GAME_ID; Line: $line; Red: $RED; Green: $GREEN; Blue: $BLUE; Power: $POWER"
done

echo $SUM
