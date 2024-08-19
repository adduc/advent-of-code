#!/usr/bin/env php
<?php declare(strict_types=1);

if ($argc !== 2) {
    fwrite(STDERR, "Usage: $argv[0] <file>\n");
    exit(1);
}

if (!file_exists($argv[1])) {
    fwrite(STDERR, "File not found: $argv[1]\n");
    exit(1);
}

// determine number of cores
$cores = preg_match_all('/^core id/m', file_get_contents('/proc/cpuinfo'));

if ($cores === false) {
    fwrite(STDERR, "Could not determine number of cores\n");
    exit(1);
}

$size = filesize($argv[1]);
$filename = tempnam(sys_get_temp_dir(), 'billion-row-challenge');

// fork processes
$children = [];

for ($i = 0; $i < $cores; $i++) {
    $pid = pcntl_fork();

    if ($pid === -1) {
        fwrite(STDERR, "Could not fork process\n");
        exit(1);
    }

    if ($pid === 0) {
        $pid = getmypid();

        $handle = fopen($argv[1], 'r');

        $stations = [];

        $start_chunk = (int)floor($size / $cores * $i);
        $end_chunk = (int)floor($size / $cores * ($i + 1));

        fseek($handle, $start_chunk);

        if ($i > 0) {
            fgets($handle);
        }

        while ($line = fgets($handle)) {
            [$name, $temp] = explode(';', $line);
            $temp = (float)$temp;

            $station = &$stations[$name];

            if ($station === NULL) {
                $station = [
                    'sum' => $temp,
                    'count' => 1,
                    'min' => $temp,
                    'max' => $temp,
                ];
            } else {

                $station = &$stations[$name];

                $station['sum'] += $temp;
                $station['count']++;
                if ($station['min'] > $temp) {
                    $station['min'] = $temp;
                } elseif ($station['max'] < $temp) {
                    $station['max'] = $temp;
                }
            }

            if (ftell($handle) >= $end_chunk) {
                break;
            }
        }

        file_put_contents($filename . '.' . $pid, json_encode($stations));
        exit(0);
    }

    $children[] = $pid;
}

$results = [];

// wait for children to finish and collect results
foreach ($children as $pid) {
    pcntl_waitpid($pid, $status);

    $stations  = json_decode(file_get_contents($filename . '.' . $pid), true);

    foreach ($stations as $name => $station) {
        if (!isset($results[$name])) {
            $results[$name] = $station;
        } else {
            $results[$name]['sum'] += $station['sum'];
            $results[$name]['count'] += $station['count'];
            if ($results[$name]['min'] > $station['min']) {
                $results[$name]['min'] = $station['min'];
            } elseif ($results[$name]['max'] < $station['max']) {
                $results[$name]['max'] = $station['max'];
            }
        }
    }
}

foreach ($results as $name => $station) {
    $mean = number_format($station['sum'] / $station['count'], 1);
    echo "{$name};{$station['min']};{$mean};{$station['max']}\n";
}
