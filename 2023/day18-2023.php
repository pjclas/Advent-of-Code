<?php

function readInput() {
    global $plan;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $p = explode(" ", trim($line));
        $p[2] = substr($p[2],2,6);
        $plan[] = $p;
    }
}

function findArea($holes): int
{
    $area = 0;
    $prevRow = 0;
    $prevCols = array();
    foreach ($holes as $row => $cols) {
        $prevCols = array_keys($prevCols);
        for ($c = 0; $c < count($prevCols); $c += 2) {
            $c1 = $prevCols[$c];
            $c2 = $prevCols[$c + 1];
            // calculate additional area
            $area += ($row - $prevRow + 1) * ($c2 - $c1 + 1);

            // now figure out next set of top points
            if (!isset($cols[$c1])) $cols[$c1] = 1;
            else unset($cols[$c1]);
            if (!isset($cols[$c2])) $cols[$c2] = 1;
            else unset($cols[$c2]);
        }
        ksort($cols);
        $keys = array_keys($cols);
        // subtract any area overlap between prevCols and Cols
        for ($c = 0; $c < count($cols); $c += 2) {
            $c1 = $keys[$c];
            $c2 = $keys[$c + 1];
            for ($pc = 0; $pc < count($prevCols); $pc += 2) {
                $pc1 = $prevCols[$pc];
                $pc2 = $prevCols[$pc + 1];
                if ($c1 < $pc1 && $c2 > $pc1 && $c2 <= $pc2) $area -= ($c2 - $pc1) + 1;
                if ($c1 >= $pc1 && $c1 < $pc2 && $c2 > $pc2) $area -= ($pc2 - $c1) + 1;
                if ($c1 < $pc1 && $c2 > $pc2) $area -= ($pc2 - $pc1) + 1;
                if ($c1 >= $pc1 && $c2 <= $pc2) $area -= ($c2 - $c1) + 1;
            }
        }
        $prevCols = $cols;
        $prevRow = $row;
    }
    return $area;
}

function part1() {
    global $plan;
    $dirs=['R'=>[0,1],'L'=>[0,-1],'D'=>[1,0],'U'=>[-1,0]];

    $holes = array();
    $r=$c=0;
    foreach ($plan as $p) {
        $r += $p[1]*$dirs[$p[0]][0];
        $c += $p[1]*$dirs[$p[0]][1];
        $holes[$r][$c] = 1;
    }
    ksort($holes);

    $area = findArea($holes);
    print "Area is $area\n";
}

function part2()
{
    global $plan;
    $dirs=[[0,1],[1,0],[0,-1],[-1,0]];
    $holes = array();
    $r=$c=0;
    foreach ($plan as $p) {
        $dir = $p[2][-1];
        $dist = hexdec(substr($p[2],0,5));
        $r += $dist*$dirs[$dir][0];
        $c += $dist*$dirs[$dir][1];
        $holes[$r][$c] = 1;
    }
    ksort($holes);

    $area = findArea($holes);
    print "Area is $area\n";
}

readInput();
part1();
part2();
