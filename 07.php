<?php
require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/07.txt');
$lines = explode("\n", $lines);

function getHandType($hand, $jokerize = false) {
    $hand = str_split($hand);
    $groups = [];
    foreach ($hand as $card) {
        if (!isset($groups[$card])) $groups[$card] = [];
        $groups[$card][] = $card;
    }
    uasort($groups, function($a, $b) {
        return count($b) - count($a);
    });

    $strengthenFirstGroupBy = 0;
    if ($jokerize && isset($groups['J'])) {
        $strengthenFirstGroupBy = count($groups['J']);
        if (count($groups) > 1) unset($groups['J']);
    }

    $groups = array_values($groups);

    $groupCount = count($groups);
    $firstGroupCount = count($groups[0]) + $strengthenFirstGroupBy;

    // five of a kind
    if ($groupCount == 1) return 7;

    // four of a kind
    if ($groupCount == 2 && $firstGroupCount == 4) return 6;

    // full house
    if ($groupCount == 2 && $firstGroupCount == 3) return 5;

    // three of a kind
    if ($groupCount == 3 && $firstGroupCount == 3) return 4;

    // two pair
    if ($groupCount == 3 && $firstGroupCount == 2) return 3;

    // one pair
    if ($groupCount == 4 && $firstGroupCount == 2) return 2;

    // high five
    return 1;
}

function getCardStrengthOrder($jokerize = false) {
    $order = ['A', 'K', 'Q', 'J', 'T', '9', '8', '7', '6', '5', '4', '3', '2'];
    if ($jokerize) {
        $order = ['A', 'K', 'Q', 'T', '9', '8', '7', '6', '5', '4', '3', '2', 'J'];
    }
    return array_reverse($order);
}

$handToBidMap = [];

foreach ($lines as $line) {
    list($hand, $bid) = explode(' ', $line);
    $handToBidMap[$hand] = $bid;
}

$hands = array_keys($handToBidMap);
$hands = array_map(fn ($el) => strval($el), $hands);

for ($part = 1; $part <= 2; $part++) {
    $jokerize = $part == 2;
    $cardStrength = getCardStrengthOrder($jokerize);
    usort($hands, function($a, $b) use ($cardStrength, $jokerize) {
        $handTypeA = getHandType($a, $jokerize);
        $handTypeB = getHandType($b, $jokerize);
        if ($handTypeA == $handTypeB) {
            for ($i = 0; $i < 5; $i++) {
                $cardA = $a[$i];
                $cardB = $b[$i];
                if ($cardA != $cardB) {
                    return array_search($cardA, $cardStrength) - array_search($cardB, $cardStrength);
                }
            }
        }
        return $handTypeA - $handTypeB;
    });

    $result = 0;
    foreach ($hands as $i => $hand) {
        $result += $handToBidMap[$hand] * ($i + 1);
    }
    echo "Part $part: $result\n";
}
