<?php

require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/03.txt');
$lines = explode("\n", $lines);

$regex = "/[^.\w]/u";
$res = 0;
foreach ($lines as $i => $line) {
    $cursor = 0;
    while (preg_match('/\d+/', $line, $matches, PREG_OFFSET_CAPTURE, $cursor)) {
        $number = (int)($matches[0][0]);
        $start = $matches[0][1];
        $cursor = $start + strlen($number);
        $end = $cursor - 1;

        // left
        if ($start > 0) {
            $leftCell = $line[$start - 1];
            if (preg_match($regex, $leftCell)) {
                $res += $number;
                continue;
            }
        }

        // right
        if ($end < strlen($line) - 1) {
            $rightCell = $line[$end + 1];
            if (preg_match($regex, $rightCell)) {
                $res += $number;
                continue;
            }
        }

        // top row
        if ($i > 0) {
            $adjStart = max($start - 1, 0);
            $x = $start == 0 ? 1 : 2;
            $topRow = substr($lines[$i - 1], $adjStart, strlen($number) + $x);
            if (preg_match($regex, $topRow)) {
                $res += $number;
                continue;
            }
        }

        // bottom row
        if ($i < count($lines) - 1) {
            $adjStart = max($start - 1, 0);
            $bottomRow = substr($lines[$i + 1], $adjStart, strlen($number) + 2);
            if (preg_match($regex, $bottomRow)) {
                $res += $number;
                continue;
            }
        }
    }
}
echo "$res\n";