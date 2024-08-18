#!/usr/bin/env php
<?php

declare(strict_types=1);


class Line
{
    public readonly array $numbers;
    public readonly array $chars;

    public function __construct(string $line) {
        [$this->numbers, $this->chars] = $this->parseLine($line);
    }

    protected function parseLine(string $line): array
    {
        $chars = [];
        $numbers = [];
        $buffer = "";

        foreach (str_split($line) as $i => $char) {
            if (is_numeric($char)) {
                $buffer .= $char;
                continue;
            }

            if ($buffer !== "") {
                $this->processNumber($buffer, $i, $numbers);
                $buffer = "";
            }

            if ($char === "*") {
                $chars[$i] = $char;
            }

        }

        return [$numbers, $chars];
    }

    protected function processNumber(string $buffer, int $position, array &$numbers): void
    {
        $number = (int)$buffer;

        foreach (range($position - strlen($buffer), $position - 1) as $j) {
            $numbers[$j] = $number;
        }
    }
}

class App
{
    protected int $sum = 0;

    public function __invoke()
    {
        $sum = 0;
        $parsed = $this->parseLines(STDIN);
        $lines = [new Line(""), new Line("")];

        while ($parsed->valid()) {
            $lines[] = $parsed->current();
            $parsed->next();
            $sum += $this->processLines(...$lines);
            array_shift($lines);
        }

        $lines[] = new Line("");
        $sum += $this->processLines(...$lines);

        return $sum;
    }

    /**
     * @return Generator<Line>
     */
    protected function parseLines($input): Generator
    {
        while ($line = fgets($input)) {
            yield new Line($line);
        }
    }

    protected function processLines(Line ...$lines): int
    {
        $sum = 0;

        foreach ($lines[1]->chars as $pos => $char) {
            $numbers = [];

            // Look at each line, and find the adjacent numbers
            foreach ($lines as $line) {
                $found = null;

                foreach (range($pos - 1, $pos + 1) as $adjacent) {
                    $number = $line->numbers[$adjacent] ?? null;

                    if (isset($number) && $found !== $number) {
                        $found = $number;
                        $numbers[] = $number;

                        if (count($numbers) > 2) {
                            // If we find more than 2 adjacent numbers, we can stop looking
                            continue 2;
                        }
                    }
                }
            }

            if (count($numbers) === 2) {
                $sum += $numbers[0] * $numbers[1];
            }
        }

        return $sum;
    }
};

$sum = (new App())();

fwrite(STDOUT, "Sum: {$sum}" . PHP_EOL);
fprintf(STDOUT, "Memory: %.2f mb" . PHP_EOL, (memory_get_peak_usage() / 1024 / 1024));
