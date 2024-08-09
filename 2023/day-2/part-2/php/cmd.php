#!/usr/bin/env php
<?php declare(strict_types=1);

$thresholds = ["red" => 12, "green" => 13, "blue" => 14];
$regex = '/(?<count>\d+) (?<color>\w+)/m';
$sum = 0;

while ($line = fgets(STDIN)) {
    $pieces = explode(":", $line);
    $colors = [];

    preg_match_all($regex, $pieces[1], $matches, PREG_SET_ORDER);

    foreach ($matches as ['color' => $color, 'count' => $count]) {
        $colors[$color] = max($colors[$color] ?? 0, $count);
    }

    $sum += array_product($colors);
}
fwrite(STDOUT, $sum . PHP_EOL);
