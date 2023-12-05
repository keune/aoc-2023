<?php
require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/05.txt');
$lines = explode("\n", $lines);

$seeds = array_shift($lines);
$seeds = str_replace('seeds: ', '', $seeds);
$seeds = collect(explode(' ', $seeds))->map(fn ($el) => intval($el));

array_shift($lines);

$mapGroups = [];
$title = null;
foreach ($lines as $line) {
    if ($line == '') {
        $title = null;
    } elseif (preg_match("/^[\d\s]+$/", $line) && $title) {
        list($to, $from, $size) = collect(explode(' ', $line))->map(fn ($el) => intval($el));
        $mapGroups[$title][] = [$from, $to, $size];
    } else {
        $title = str_replace(' map:', '', $line);
    }
}

$properties = ['seed', 'soil', 'fertilizer', 'water', 'light', 'temperature', 'humidity', 'location'];
$result = [];

foreach ($seeds as $seed) {
    $seedData = [
        'seed' => $seed,
    ];
    for ($i = 0; $i < count($properties) - 1; $i++) {
        $mapGroupKey = $properties[$i].'-to-'.$properties[$i+1];
        $fromValue = $seedData[$properties[$i]];

        $toValue = null;
        $maps = $mapGroups[$mapGroupKey];
        foreach ($maps as $map) {
            list ($from, $to, $size) = $map;
            if ($fromValue >= $from && $fromValue <= $from + $size) {
                $toValue = $to + ($fromValue - $from);
                break;
            }
        }
        if ($toValue === null) $toValue = $fromValue;
        $seedData[$properties[$i+1]] = $toValue;
    }
    dd($seedData);
    $result[] = $seedData;
}

echo collect($result)->sortBy('location')->first()['location'];