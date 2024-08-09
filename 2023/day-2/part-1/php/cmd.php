#!/usr/bin/env php
<?php declare(strict_types=1);

$thresholds = [
    "red" => 12,
    "green" => 13,
    "blue" => 14,
];

$regex = '/(?<count>\d+) (?<color>\w+)/m';

$sum = 0;

while ($line = fgets(STDIN)) {

    $pieces = explode(":", $line);

    $game_id = substr($pieces[0], 5);

    $colors = [];

    preg_match_all($regex, $pieces[1], $matches, PREG_SET_ORDER);

    foreach ($matches as $match) {
        $colors[$match['color']] = max($colors[$match['color']] ?? 0, $match['count']);
    }

    foreach ($colors as $color => $count) {
        if ($count > $thresholds[$color]) {
            continue 2;
        }
    }

    $sum += $game_id;
}

fwrite(STDOUT, $sum . PHP_EOL);
