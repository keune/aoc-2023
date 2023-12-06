<?php
require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/06.txt');
list($times, $distances) = explode("\n", $lines);
$times = trim(str_replace('Time:', '', $times));
$times = collect(preg_split("/\s+/", $times))->map(fn ($el) => intval($el));
$distances = trim(str_replace('Distance:', '', $distances));
$distances = collect(preg_split("/\s+/", $distances))->map(fn ($el) => intval($el));

$races = [];
foreach ($times as $i => $time) {
    $distance = $distances[$i];
    $totalWays = 0;
    for ($hold = 0; $hold <= $time; $hold++) {
        $timeLeft = $time - $hold;
        $score = $timeLeft * $hold; // $hold = speed
        if ($score > $distance) {
            $totalWays++;
        }
    }
    $races[] = $totalWays;
}

echo 'Part 1: '.array_product($races)."\n";

$time = intval($times->join(''));
$distance = intval($distances->join(''));

$x = $time / 2;
$y = $x;
$tmp = $x * $y;
$counter = 0;
while ($tmp > $distance) {
    $counter++;
    $x += 1;
    $y -= 1;
    $tmp = $x * $y;
}

echo 'Part 2: '.(($counter * 2) - 1)."\n";