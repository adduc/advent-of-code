#!/usr/bin/env php
<?php declare(strict_types=1);

if ($argc !== 2) {
    echo "Usage: $argv[0] <file>\n";
    exit(1);
}

$handle = fopen($argv[1], 'r');

$stations = [];

while ($line = fgets($handle)) {
    [$name, $temp] = explode(';', $line);
    $temp = (float)$temp;

    if (!isset($stations[$name])) {
        $stations[$name] = [
            'sum' => $temp,
            'count' => 1,
            'min' => $temp,
            'max' => $temp,
        ];
    } else {
        $stations[$name]['sum'] += $temp;
        $stations[$name]['count']++;
        if ($stations[$name]['min'] > $temp) {
            $stations[$name]['min'] = $temp;
        } elseif ($stations[$name]['max'] < $temp) {
            $stations[$name]['max'] = $temp;
        }
    }
}

foreach ($stations as $name => $station) {
    $mean = number_format($station['sum'] / $station['count'], 1);
    echo "{$name};{$station['min']};{$mean};{$station['max']}\n";
}
