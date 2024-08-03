#!/bin/bash
##
# Usage: cat input.txt | ./cmd.sh
##

SUM=0

declare -A MAP=( [one]=1 [two]=2 [three]=3 [four]=4 [five]=5 [six]=6 [seven]=7 [eight]=8 [nine]=9 )

while read line; do

  INDEX=0
  LENGTH=${#line}

  BUFFER=""
  NUMBERS=""

  # iterate through each character
  while [ $INDEX -lt $LENGTH ]; do

    # get the current character
    CHAR=${line:INDEX:1}

    # if the character is a number, add it to the numbers and reset the
    # buffer
    if [[ $CHAR =~ [0-9] ]]; then
      NUMBERS="$NUMBERS$CHAR"
      BUFFER=""
    else
      # otherwise, add it to the buffer and check for the presence of a
      # numeric word anywhere in the buffer
      BUFFER="$BUFFER$CHAR"
      TMP_INDEX=0
      TMP_LENGTH=${#BUFFER}

      # iterate through the buffer to look for a numeric word
      while [ $TMP_INDEX -lt $TMP_LENGTH ]; do
        TEMP_BUFFER=${BUFFER:TMP_INDEX}

        if [ ${MAP[$TEMP_BUFFER]+_} ]; then
          NUMBERS="$NUMBERS${MAP[$TEMP_BUFFER]}"
          break
        fi

        TMP_INDEX=$((TMP_INDEX + 1))
      done
    fi

    # move to the next character
    INDEX=$((INDEX + 1))
  done

  # Get the first and last number
  NUMBER=${NUMBERS:0:1}${NUMBERS: -1}

  # Add the number to the sum
  SUM=$((SUM + NUMBER))
done

echo $SUM