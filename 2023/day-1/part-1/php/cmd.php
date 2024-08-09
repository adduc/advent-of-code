#!/usr/bin/env php
<?php declare(strict_types=1);

$sum = 0;

while ($line = fgets(STDIN)) {
    [$first, $last] = [null, null];

    // iterate through each character in the line, pulling out th
    // first number we find, and the last number we find
    foreach (str_split($line) as $char) {
        if (is_numeric($char)) {
            $last = (int) $char;
            if ($first === null) {
                $first = $last;
            }
        }
    }

    $sum += $first*10 + $last;
}

fwrite(STDOUT, $sum . PHP_EOL);
