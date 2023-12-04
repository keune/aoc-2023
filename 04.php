<?php

require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/04.txt');
$lines = explode("\n", $lines);

$part1 = 0;
$cardCounts = array_fill(0, count($lines), 1);

foreach ($lines as $i => $line) {
    $numbers = explode(': ', $line)[1];
    $numbers = explode(' | ', $numbers);
    $winners = [];
    $user = [];
    preg_match_all("/\d+/", $numbers[0], $winners);
    preg_match_all("/\d+/", $numbers[1], $user);
    $winners = $winners[0];
    $user = $user[0];
    $totalWin = count(array_intersect($user, $winners));

    for ($j = 1; $j <= $totalWin; $j++) {
        if ($i + $j < count($lines)) {
            $cardCounts[$i + $j] += $cardCounts[$i];
        } else {
            break;
        }
    }
    if ($totalWin) {
        if ($totalWin == 1) {
            $part1 += 1;
        } else {
            $part1 += pow(2, $totalWin - 1);
        }
    }
}

echo "Part 1: $part1\n";
echo 'Part 2: '.array_sum($cardCounts)."\n";