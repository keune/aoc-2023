<?php

require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/02.txt');
$lines = explode("\n", $lines);

$gameMaxValues = [];

foreach ($lines as $line) {
    list($gameId, $rawPicks) = explode(': ', $line);
    $gameId = str_replace('Game ', '', $gameId);
    $rawPicks = explode('; ', $rawPicks);
    $maxes = ['red' => 0, 'green' => 0, 'blue' => 0];
    foreach ($rawPicks as $rawPick) {
        $picks = explode(', ', $rawPick);
        foreach ($picks as $pick) {
            list ($count, $color) = explode(' ', $pick);
            $count = intval($count);
            if ($count > $maxes[$color]) {
                $maxes[$color] = $count;
            }
        }
    }
    $gameMaxValues[$gameId] = $maxes;
}

$possibleGameIds = array_keys(
    array_filter($gameMaxValues, function($item) {
        return ($item['red'] <= 12) && ($item['green'] <= 13) && ($item['blue'] <= 14);
    })
);
echo 'Part 1: '.array_sum($possibleGameIds)."\n";

$part2 = array_sum(
    array_map(function($item) {
        return array_product(array_values($item));
    }, $gameMaxValues)
);
echo "Part 2: {$part2}\n";
