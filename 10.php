<?php
require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/10.txt');
$map = collect(explode("\n", $lines))->map(fn ($el) => collect(str_split($el)));

function getKey($row, $col) {
    return "{$row}_{$col}";
}

function findAdjacents($map, $r, $c) {
    $functions = [
        'N' => function() use ($r, $c, &$adjacents, $map) {
            if ($r > 0) {
                if ($map[$r - 1][$c] != '.') $adjacents[] = [$r - 1, $c];
            }
        },
        'W' => function() use ($r, $c, &$adjacents, $map) {
            if ($c > 0) {
                if ($map[$r][$c - 1] != '.') $adjacents[] = [$r, $c - 1];
            }
        },
        'E' => function() use ($r, $c, &$adjacents, $map) {
            if ($c < count($map[$r]) - 1) {
                if ($map[$r][$c + 1] != '.') $adjacents[] = [$r, $c + 1];
            }
        },
        'S' => function() use ($r, $c, &$adjacents, $map) {
            if ($r < count($map) - 1) {
                if ($map[$r + 1][$c] != '.') $adjacents[] = [$r + 1, $c];
            }
        }
    ];

    $fnMap = [
        '|' => ['N', 'S'],
        '-' => ['E', 'W'],
        'L' => ['N', 'E'],
        'J' => ['N', 'W'],
        '7' => ['S', 'W'],
        'F' => ['S', 'E'],
        'S' => [],
    ];

    $tile = $map[$r][$c];
    $adjacents = [];
    $useFunctions = $fnMap[$tile];
    foreach ($useFunctions as $function) {
        $functions[$function]();
    }

    return $adjacents;
}

$sr = null;
$sc = null;
foreach ($map as $ri => $r) {
    if ($r->contains('S')) {
        $sr = $ri;
        $sc = $r->search('S');
    }
}
$sKey = getKey($sr, $sc);

$adjList = [];

foreach ($map as $r => $row) {
    foreach ($row as $c => $col) {
        if ($col != '.') {
            $key = getKey($r, $c);
            $adjacents = findAdjacents($map, $r, $c);
            $adjList[$key] = collect($adjacents)->map(fn ($el) => getKey($el[0], $el[1]));
        }
    }
}

// get S's adjacents by searching in everyone else's adjacents
$sAdjacents = [];
foreach ($adjList as $tile => $adjKeys) {
    if ($adjKeys->contains($sKey)) $sAdjacents[] = $tile;
}
$adjList[$sKey] = $sAdjacents;

$distance = 0;
$visited = [$sKey => true];
$frontier = [$sKey];
while (count($frontier)) {
    $next = [];
    foreach ($frontier as $adjKey) {
        $adjacents = $adjList[$adjKey] ?? [];
        foreach ($adjacents as $adjacent) {
            if (!in_array($adjacent, $next) && !isset($visited[$adjacent])) {
                $next[] = $adjacent;
                $visited[$adjacent] = true;
            }
        }
    }
    $frontier = $next;
    if (count($next)) $distance++;
}

$down = ['|', '7', 'F'];
$counter = 0;
foreach ($map as $ri => $row) {
    $imIn = false;
    foreach ($row as $ci => $col) {
        $key = getKey($ri, $ci);
        if (in_array($col, $down) && isset($visited[$key])) {
            $imIn = !$imIn;
        }
        if ($imIn && !isset($visited[$key])) {
            $counter++;
        }
    }
}

echo "Part 1: $distance\n";
echo "Part 2: $counter\n";
