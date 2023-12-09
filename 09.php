<?php
require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/09.txt');
$report = collect(explode("\n", $lines))
    ->map(
        fn ($line) => collect([
            collect(explode(' ', $line))
            ->map(fn ($el) => intval($el))
        ])
    );

$part1 = 0;
$part2 = 0;
foreach ($report as $reportItems) {
    while (true) {
        $lastLine = $reportItems->last();
        if ($lastLine->every(fn ($el) => $el == 0)) break;
        $nextLine = [];
        for ($i = 0, $j = 1; $j < count($lastLine); $i++, $j++) {
            $nextLine[] = $lastLine[$j] - $lastLine[$i];
        }
        $reportItems->push(collect($nextLine));
    }

    for ($i = count($reportItems) - 1; $i > 0; $i--) {
        $ep = $reportItems[$i]->last() + $reportItems[$i - 1]->last();
        $reportItems[$i - 1]->push($ep);

        $ep = $reportItems[$i - 1]->first() - $reportItems[$i]->first();
        $reportItems[$i - 1]->prepend($ep);
    }
    $part1 += $reportItems[0]->last();
    $part2 += $reportItems[0]->first();
}

echo "Part 1: $part1 \n";
echo "Part 2: $part2 \n";