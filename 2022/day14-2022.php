<?php

$INT_MAX = 0x7FFFFFFF;

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);

// initialize grid
$grid = array();
$origGrid = array();
foreach (range(0,999) as $k) {
    $grid[$k] = array_fill(0, 1000, '.');
}

$mD = [1,  1, 1];
$mR = [0, -1, 1];
$floor = 0;

function readInput() {
    global $grid, $floor, $origGrid;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $points = explode(" -> ", trim($line));
        list($c1, $r1) = explode(",", $points[0]);
        for ($i=1; $i<count($points); $i++) {
            list($c2, $r2) = explode(",", $points[$i]);
            //echo "point = $r1,$c1 -> $r2,$c2\n";
            foreach(range($r1, $r2) as $r) {
                foreach(range($c1, $c2) as $c) {
                    // add wall
                    $grid[$r][$c] = "#";
                }
            }
            // update previous point
            $r1 = $r2;
            $c1 = $c2;

            $floor = max($floor, $r1);
        }
    }
    $origGrid = $grid;
}

function printGrid() {
    global $grid;
    echo "\n\n";
    foreach ($grid as $row) {
        foreach ($row as $val) {
            echo $val;
        }
        echo "\n";
    }
    echo "\n\n";
}
function part1() {
    global $grid;
    $s=0;
    do {
        $p = moveSand(0,500);
        if ($p !== false) {
            $grid[$p[0]][$p[1]] = 'o';
            $s++;
        }
    } while ($p !== false);

    echo "Part 1: Sand grains = $s\n";
}

function part2() {
    global $grid, $origGrid, $floor;
    $grid = $origGrid;
    $floor+=2;
    $grid[$floor] = array_fill(0, 1000, '#');

    $s=0;
    do {
        $p = moveSand(0,500);
        if ($p !== false) {
            $grid[$p[0]][$p[1]] = 'o';
            $s++;
        }
    } while ($p != [0,500]);

    echo "Part 2: Sand grains = $s\n";
}

function moveSand($r,$c) {
    global $grid, $mD, $mR;
//    echo "checking $r, $c = ".$grid[$r][$c]."\n";
    foreach ($mD as $k=>$rr) {
        // check if we fell into the abyss
        if ($r+$rr == count($grid)) return false;
        else if ($grid[$r+$rr][$c+$mR[$k]] == '.') {
            return moveSand($r+$rr, $c+$mR[$k]);
        }
    }

    // we are stopped, update the grid
    return [$r,$c];
}

readInput();

part1();
part2();

?>
