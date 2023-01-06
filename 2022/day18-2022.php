<?php

$cubes = array();
$mx = $my = $mz = 0;
$dirX = [1,-1,0,0,0,0];
$dirY = [0,0,1,-1,0,0];
$dirZ = [0,0,0,0,1,-1];

function readInput() {
    global $cubes;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $cubes[trim($line)] = explode(",", trim($line));
    }
}

function surfaceArea($cubes) {
    global $dirX, $dirY, $dirZ;
    $connected = array();
    $area = 6*count($cubes);
    foreach ($cubes as $k1=>$c1) {
        foreach ($dirX as $i=>$o) {
            $k2 = isset($cubes[$c1[0]+$dirX[$i].",".$c1[1]+$dirY[$i].",".$c1[2]+$dirZ[$i]]);
            if ($k2 !== false) $area-=1;
        }
    }

    return $area;
}

function findPocket($cubes, $x,$y,$z, $pocket = array()) {
    global $dirX, $dirY, $dirZ, $mx, $my, $mz;

    // if we are not on a real block then we are still exploring this air pocket
    if (!isset($cubes["$x,$y,$z"]) && !isset($pocket["$x,$y,$z"])) {
        // check if we have reached the outside of the block
        if ($x==1 || $y==1 || $z==1 ||
            $x==$mx || $y==$my || $z==$mz) {
            // this is not an air pocket since it is exposed to the outside
            return false;
        }

        // add to explored air block list
        $pocket["$x,$y,$z"] = [$x,$y,$z];

        // check all directions
        for ($i=0; $i<count($dirX); $i++) {
            $pocket = findPocket($cubes,$x+$dirX[$i],$y+$dirY[$i],$z+$dirZ[$i], $pocket);
            if ($pocket === false) break;
        }
    }

    return $pocket;
}

function part1() {
    global $cubes;
    $area = surfaceArea($cubes);

    echo "Part 1: Surface area = $area\n";
}

function part2() {
    global $cubes, $mx, $my, $mz;

    $area = surfaceArea($cubes);

    // find max cube coordinates
    $mx = $my = $mz = 0;
    foreach($cubes as $c) {
        if ($c[0]>$mx) $mx=$c[0];
        if ($c[1]>$my) $my=$c[1];
        if ($c[2]>$mz) $mz=$c[2];
    }

    $pockets = array();
    // now check each open space to see if it's part of an air pocket
    for ($x=1; $x<=$mx; $x++) {
        for ($y=1; $y<=$my; $y++) {
            for ($z=1; $z<=$mz; $z++) {
                if (!isset($cubes["$x,$y,$z"]) && !isset($pockets["$x,$y,$z"])) {
                    // check if this block is part of a pocket of n or more spaces
                    $p = findPocket($cubes, $x,$y,$z);
                    if ($p !== false) {
                        $pockets = array_merge($pockets, $p);
                    }
                }
            }
        }
    }

    // now subtract surface area of pockets from our surface area
    $area -= surfaceArea($pockets);

    echo "Part 2: Surface area = $area\n";
}

readInput();
$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";

