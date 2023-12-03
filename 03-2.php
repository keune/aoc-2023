<?php

require __DIR__.'/vendor/autoload.php';

$lines = file_get_contents('inputs/03.txt');
$lines = explode("\n", $lines);

function getPosKey($i, $j) {
    return "{$i}_{$j}";
}

function getFullNumber($i, $j, &$usedPositions) {
    global $lines;
    $line = $lines[$i];
    $posKey = getPosKey($i, $j);
    if (!is_numeric($line[$j]) || in_array($posKey, $usedPositions)) {
        return false;
    }
    $number = $line[$j];
    $usedPositions[] = $posKey;

    for ($goLeft = $j - 1; $goLeft >= 0; $goLeft--) {
        if (is_numeric($line[$goLeft])) {
            $number = $line[$goLeft].$number;
            $usedPositions[] = getPosKey($i, $goLeft);
        } else {
            break;
        }
    }

    for ($goRight = $j + 1; $goRight < strlen($line); $goRight++) {
        if (is_numeric($line[$goRight])) {
            $number .= $line[$goRight];
            $usedPositions[] = getPosKey($i, $goRight);
        } else {
            break;
        }
    }
    return $number;
}

$res = 0;
foreach ($lines as $i => $line) {
    $cursor = 0;
    while ($starPos = strpos($line, '*', $cursor)) {
        $cursor = $starPos + 1;

        $adjacentNumbers = [];
        $usedPositions = [];

        if ($i > 0) {
            // top left
            if ($starPos > 0) {
                $fr = getFullNumber($i-1, $starPos-1, $usedPositions);
                if ($fr) $adjacentNumbers[] = $fr;
            }
            
            // top
            $fr = getFullNumber($i-1, $starPos, $usedPositions);
            if ($fr) $adjacentNumbers[] = $fr;

            // top right
            if ($starPos < strlen($lines[$i]) - 1) {
                $fr = getFullNumber($i-1, $starPos+1, $usedPositions);
                if ($fr) $adjacentNumbers[] = $fr;
            }
        }

        // left
        if ($starPos > 0) {
            $fr = getFullNumber($i, $starPos-1, $usedPositions);
            if ($fr) $adjacentNumbers[] = $fr;
        }

        // right
        if ($starPos < strlen($lines[$i]) - 1) {
            $fr = getFullNumber($i, $starPos+1, $usedPositions);
            if ($fr) $adjacentNumbers[] = $fr;
        }

        if ($i < count($lines) - 1) {
            // bottom left
            if ($starPos > 0) {
                $fr = getFullNumber($i+1, $starPos-1, $usedPositions);
                if ($fr) $adjacentNumbers[] = $fr;
            }

            // bottom
            $fr = getFullNumber($i+1, $starPos, $usedPositions);
            if ($fr) $adjacentNumbers[] = $fr;

            // bottom right
            $fr = getFullNumber($i+1, $starPos+1, $usedPositions);
            if ($fr) $adjacentNumbers[] = $fr;
        }
        
        if (count($adjacentNumbers) == 2) {
            $res += array_product($adjacentNumbers);
        }
    }
}
echo "$res\n";