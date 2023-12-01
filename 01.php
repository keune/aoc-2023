<?php
require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/01.txt');
$lines = explode("\n", $lines);

$part1 = array_reduce($lines, function ($sum, $line) {
    $nums = preg_replace("/[^\d]/", '', $line);
    $nums = str_split($nums);
    $sum += intval($nums[0].$nums[array_key_last($nums)]);
    return $sum;
}, 0);
echo "Part 1: $part1\n";

$strDigits = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
$digits = array_map('strval', range(1, 9));
$all = array_merge($digits, $strDigits);

$part2 = array_reduce($lines, function ($sum, $line) use ($strDigits, $digits, $all) {
    $first = null;
    $last = null;

    for ($i = 0; $i < strlen($line); $i++) {
        for ($j = 1; $j <= strlen($line) - $i; $j++) {
            $sub = substr($line, $i, $j);
            if (in_array($sub, $all)) {
                if (in_array($sub, $strDigits)) {
                    $first = array_search($sub, $strDigits) + 1;
                } else {
                    $first = $sub;
                }
                break 2;
            }
        }
    }
    
    for ($i = strlen($line) - 1; $i >= 0; $i--) {
        for ($j = 1; $j <= strlen($line) - $i; $j++) {
            $sub = substr($line, $i, $j);
            if (in_array($sub, $all)) {
                if (in_array($sub, $strDigits)) {
                    $last = array_search($sub, $strDigits) + 1;
                } else {
                    $last = $sub;
                }
                break 2;
            }
        }
    }

    return $sum + intval($first.$last);
}, 0);
echo "Part 2: $part2\n";
