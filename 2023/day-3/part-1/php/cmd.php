#!/usr/bin/env php
<?php

declare(strict_types=1);

(new class {
    protected int $sum = 0;

    public function __invoke()
    {
        $numbers = [];
        $chars = [];

        while ($line = fgets(STDIN)) {
            [$sum, $numbers, $chars] = $this->parseLine($line, $numbers, $chars);
            $this->sum += $sum;
        }

        fwrite(STDOUT, $this->sum . PHP_EOL);
    }

    public function parseLine(string $line, array $last_numbers, array $last_chars): array
    {
        $sum = 0;
        $numbers = [];
        $chars = [];

        $buffer = "";

        // turn buffer into number, store positions in numbers array
        $process_buffer = function (int $i) use (&$buffer, &$numbers) {
            if ($buffer === "") {
                return;
            }

            $number = (int)$buffer;

            foreach (range($i - strlen($buffer), $i - 1) as $j) {
                $numbers[$j] = &$number;
                $buffer = "";
            }
        };

        // check for any characters adjacent to characters
        $process_numbers = function(array &$numbers, array &$chars): int {
            $sum = 0;

            foreach ($numbers as $i => &$number) {
                if ($number === 0) {
                    continue;
                }

                if (isset($chars[$i - 1]) || isset($chars[$i]) || isset($chars[$i + 1])) {
                    $sum += $number;
                    $number = 0;
                }
            }

            return $sum;
        };

        // process each character
        foreach (str_split(trim($line)) as $i => $char) {
            if (is_numeric($char)) {
                $buffer .= $char;
                continue;
            } elseif ($char === '.') {
                $process_buffer($i);
            } else {
                $process_buffer($i);
                $chars[$i] = $char;
            }
        }

        // handle any remaining buffer
        $process_buffer($i);

        // process combination of numbers and characters
        $sum += $process_numbers($last_numbers, $chars);
        $sum += $process_numbers($numbers, $last_chars);
        $sum += $process_numbers($numbers, $chars);

        return [$sum, $numbers, $chars];
    }
})();
