<?php

require __DIR__.'/vendor/autoload.php';

$steps = file_get_contents('inputs/15.txt');
$steps = explode(',', $steps);

function hasho($str) {
    $res = 0;
    $chars = str_split($str);
    foreach ($chars as $char) {
        $res += ord($char);
        $res *= 17;
        $res = $res % 256;
    }
    return $res;
}

$part1 = 0;
foreach ($steps as $step) {
    $hash = hasho($step);
    $part1 += $hash;
}

echo "Part 1: $part1 \n";

$map = collect([]);

foreach ($steps as $step) {
    if (strpos($step, '-')) {
        $label = substr($step, 0, -1);
        $hash = hasho($label);
        if (isset($map[$hash])) {
            $map[$hash] = $map[$hash]->reject(fn ($el) => $el[0] == $label)->values();
        }
    } else {
        [$label, $len] = explode('=', $step);
        $hash = hasho($label);
        if (!isset($map[$hash])) $map[$hash] = collect([]);

        $key = $map[$hash]->search(fn ($el) => $el[0] == $label);
        if ($key !== false) {
            $map[$hash][$key] = [$label, $len];
        } else {
            $map[$hash][] = [$label, $len];
        }
    }
}
$map = $map->reject(fn ($el) => $el->count() == 0);

$part2 = 0;
foreach ($map as $pos => $lenses) {
    foreach ($lenses as $i => $lens) {
        $part2 += ($pos + 1) * ($i + 1) * $lens[1];
    }
}
echo "Part 2: $part2 \n";
