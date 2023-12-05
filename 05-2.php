<?php
require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/05.txt');
$lines = explode("\n", $lines);

$seeds = array_shift($lines);
$seeds = str_replace('seeds: ', '', $seeds);
$seedGroups = collect(explode(' ', $seeds))->map(fn ($el) => intval($el))->split(2);

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


for ($tryLocation = 0; $tryLocation < 1500000000; $tryLocation++) {
    $seedData = [
        'location' => $tryLocation,
    ];
    for ($i = count($properties) - 1; $i > 0; $i--) {
        $mapGroupKey = $properties[$i-1].'-to-'.$properties[$i];
        $toValue = $seedData[$properties[$i]];

        $fromValue = null;
        $maps = $mapGroups[$mapGroupKey];
        foreach ($maps as $map) {
            list ($from, $to, $size) = $map;
            if ($toValue >= $to && $toValue <= $to + $size) {
                $fromValue = $from + ($toValue - $to);
                break;
            }
        }
        if ($fromValue === null) $fromValue = $toValue;
        $seedData[$properties[$i-1]] = $fromValue;
    }
    
    foreach ($seedGroups as $seedGroup) {
        if ($seedData['seed'] >= $seedGroup[0] && $seedData['seed'] <= $seedGroup[0] + $seedGroup[1]) {
            echo $seedData['location']."\n";
            exit;
        }
    }
}

echo "could not find result.\n";
