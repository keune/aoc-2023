<?php
require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/08.txt');
$lines = explode("\n", $lines);

$instructions = str_split($lines[0]);

$maps = [];
collect($lines)->slice(2)->values()->each(function($line) use (&$maps) {
    list ($node, $toNodes) = explode(' = ', $line);
    $toNodes = substr($toNodes, 1, strlen($toNodes) - 2);
    $toNodes = explode(', ', $toNodes);
    $maps[$node] = $toNodes;
});

function getCount($startNode, $fullZ = true) {
    global $maps, $instructions;
    $counter = 0;
    $instuctIndex = 0;
    $currentNode = $startNode;
    
    $isFinalNode = function($node) use ($fullZ) {
        if ($fullZ) return $node == 'ZZZ';
        return $node[-1] == 'Z';
    };

    while (true) {
        $instruction = $instructions[$instuctIndex];
        $toNodeIndex = $instruction == 'R' ? 1 : 0;
        $toNode = $maps[$currentNode][$toNodeIndex];
        $counter++;
        if ($isFinalNode($toNode)) break;
        $currentNode = $toNode;
        $instuctIndex++;
        $instuctIndex = $instuctIndex % count($instructions);
    }

    return $counter;
}

function gcd($a, $b) {
    while ($b != 0) {
        $temp = $b;
        $b = $a % $b;
        $a = $temp;
    }
    return $a;
}

function lcm($a, $b) {
    return ($a / gcd($a, $b)) * $b;
}

$part1 = getCount('AAA');
echo "Part 1: $part1 \n";

$startNodes = collect($maps)->keys()->filter(fn ($val, $key) => $val[-1] == 'A');

$counts = collect($startNodes)->map(fn ($startNode) => getCount($startNode, false));

$part2 = collect($counts)->reduce(fn ($carry, $item) => lcm($carry, $item), 1);

echo "Part 2: $part2 \n";
