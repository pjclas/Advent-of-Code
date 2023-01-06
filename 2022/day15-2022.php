<?php

define("INT_MAX", 2147483647);
define("INT_MIN", -2147483648);

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);

$map = array();
$sensors = array();
$maxR = $maxC = 0;
$ro = $co = 0;
$wall = array();
$p = 0;

function readInput() {
    global $map, $sensors, $maxR, $maxC, $ro, $co;
    $maxR = $maxC = $minR = $minC = 0;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        preg_match_all('!-?\d+!', $line, $data);
        list($sc,$sr,$bc,$br) = $data[0];
        $sensors[] = $data[0];

        // update cave size
        $d = abs($sr-$br) + abs($sc-$bc);
        if ($maxR < $sr+$d || $maxR < $br) $maxR = max($br, $sr+$d);
        if ($maxC < $sc+$d || $maxC < $bc) $maxC = max($bc, $sc+$d);
        if ($minR > $sr-$d || $minR > $br) $minR = min($br, $sr-$d);
        if ($minC > $sc-$d || $minC > $bc) $minC = min($bc, $sc-$d);
    }

    // shift grid to start at 0
    $ro = -1*$minR;
    $co = -1*$minC;
    $maxR += $ro;
    $maxC += $co;
}

function part1() {
    global $wall, $map, $sensors, $maxR, $maxC, $ro, $co;

    $t = 10;
    // add the known data
    foreach($sensors as $s) {
        list($sc,$sr,$bc,$br) = $s;
        if ($sr == $t || $br == $t) $p++;
        $d = abs($sr-$br) + abs($sc-$bc);
        $p = abs($sr-$r) + abs($sc-$c);
        if ($p <= $d) {
            // this sensor contains this row, count spaces
            $col = $sc + $d - abs($sr-$r) + 1;
            if ($col > $nc) {
                $nc = $col;
            }
        }
    }
    echo "Part 1: Positions scanned = ".count($wall)-$p."\n";
}

// find rightmost sensor containing point
// returns first column outside of furthest right sensor containing point
// returns false if not in any sensor
function checkSensors($r, $c) {
    global $sensors;
    $nc = $c;
    foreach ($sensors as $s) {
        list($sc,$sr,$bc,$br) = $s;
        $d = abs($sr-$br) + abs($sc-$bc);
        $p = abs($sr-$r) + abs($sc-$c);
        if ($p <= $d) {
            // this point is in the sensor's area, move to end of this sensor
            $col = $sc + $d - abs($sr-$r) + 1;
            if ($col > $nc) {
                $nc = $col;
            }
        }
    }
    if ($nc == $c) {
        // this point is not in any sensors
        return false;
    }

    return $nc;
}

function part2() {
    $max = 4000000;
    foreach (range(0,$max) as $row) {
        if ($row%100000 == 0) echo "Row $row\n";
        $nc=0;
        do {
            $col = $nc;
            $nc = checkSensors($row, $col);
        } while ($nc !== false && $nc<$max);
        if ($nc === false) break;
    }
    echo "Part 2: loc = $col,$row\n";
    echo "Part 2: Tuning frequency = ".(4000000*$col+$row)."\n";
}

readInput();

part1();
//part2();

?>
