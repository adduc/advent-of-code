#!/usr/bin/env php
<?php declare(strict_types=1);

const NUMBERS = [
    'zero',
    'one',
    'two',
    'three',
    'four',
    'five',
    'six',
    'seven',
    'eight',
    'nine',
];

$sum = 0;

while ($line = fgets(STDIN)) {
    [$first, $last, $buffer] = [null, null, ""];

    // iterate through each character in the line, pulling out th
    // first number we find, and the last number we find
    foreach (str_split($line) as $char) {
        if (is_numeric($char)) {
            $last = (int) $char;
            if ($first === null) {
                $first = $last;
            }

            $buffer = "";
            continue;
        }

        $buffer .= $char;
        $length = strlen($buffer);
        foreach(range(0, $length) as $i) {
            $sub = substr($buffer, $i);

            if (in_array($sub, NUMBERS)) {
                $last = array_search($sub, NUMBERS);
                if ($first === null) {
                    $first = $last;
                }
            }
        }
    }

    $sum += $first*10 + $last;
}

fwrite(STDOUT, $sum . PHP_EOL);
