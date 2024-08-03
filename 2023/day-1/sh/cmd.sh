#!/usr/sbin/busybox sh
##
# Usage: cat input.txt | ./cmd.sh
##

SUM=0

while read line; do
  # Strip all alpha characters
  NUMBERS=$(echo $line | tr -d '[:alpha:]')

  # Get the first and last number
  NUMBER=${NUMBERS:0:1}${NUMBERS: -1}

  # Add the number to the sum
  SUM=$((SUM + NUMBER))
done

echo $SUM