<?php
require __DIR__.'/vendor/autoload.php';

use Illuminate\Support\Collection;

$lines = file_get_contents('inputs/11.txt');
$map = collect(explode("\n", $lines))->map(fn ($el) => collect(str_split($el)));

$cols = $map->first()->count();
$rows = $map->count();

$emptyRowIndexes = [];
$emptyColIndexes = [];

for ($i = 0; $i < $rows; $i++) {
    if ($map[$i]->every(fn ($el) => $el == '.')) {
        $emptyRowIndexes[] = $i;
    }
}

for ($i = 0; $i < $cols; $i++) {
    if ($map->every(fn($el) => $el[$i] == '.')) {
        $emptyColIndexes[] = $i;
    }
}

// define galaxies
$galaxies = [];
foreach ($map as $i => $row) {
    $row->each(function($el, $j) use (&$galaxies, $i) {
        if ($el == '#') {
            $galaxies[] = [$i, $j];
        }
    });
}

$solve = function ($expansion) use ($galaxies, $emptyRowIndexes, $emptyColIndexes) {
    $totalManhattan = 0;
    $totalGlx = count($galaxies);
    for ($i = 0; $i < $totalGlx - 1; $i++) {
        for ($j = $i + 1; $j < $totalGlx; $j++) {
            $r1 = $galaxies[$i][0];
            $r2 = $galaxies[$j][0];
            $c1 = $galaxies[$i][1];
            $c2 = $galaxies[$j][1];
    
            $totalExpand = 0;
            $minR = min($r1, $r2);
            $maxR = max($r1, $r2);
            foreach ($emptyRowIndexes as $emptyRowIndex) {
                if ($minR < $emptyRowIndex && $maxR > $emptyRowIndex) {
                    $totalExpand++;
                }
            }
            $minC = min($c1, $c2);
            $maxC = max($c1, $c2);
            foreach ($emptyColIndexes as $emptyColIndex) {
                if ($minC < $emptyColIndex && $maxC > $emptyColIndex) {
                    $totalExpand++;
                }
            }
            
            $totalManhattan += abs($r1 - $r2) + abs($c1 - $c2) + ($totalExpand * ($expansion - 1));
        }
    }
    return $totalManhattan;
};

echo 'Part 1: '.$solve(2)."\n";
echo 'Part 1: '.$solve(1000000)."\n";
