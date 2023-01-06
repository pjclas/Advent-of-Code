<?php

$dirs = array();
function readInput(): void
{
    global $dirs;
    $lines = file('input.txt');
    $dirs = explode(", ", trim($lines[0]));
}

function part1(): void
{
    global $dirs;
    $m = [[-1,0],[0,1],[1,0],[0,-1]];
    $pos = [0,0];
    $f = 0;
    foreach ($dirs as $dir) {
        $steps = intval(substr($dir, 1));
        if (str_contains($dir, "R")) {
            $f = ($f+1)%4;
        } else {
            $f = ($f+3)%4;
        }
        $pos = [$pos[0]+$m[$f][0]*$steps, $pos[1]+$m[$f][1]*$steps];
    }
    $distance = abs($pos[0])+abs($pos[1]);

    echo "Part 1: Number of blocks = $distance\n";
}

function part2(): void
{
    global $dirs;
    $visited["0,0"] = 1;
    $m = [[-1,0],[0,1],[1,0],[0,-1]];
    $pos = [0,0];
    $f = 0;
    foreach ($dirs as $dir) {
        $steps = intval(substr($dir, 1));
        if (str_contains($dir, "R")) {
            $f = ($f+1)%4;
        } else {
            $f = ($f+3)%4;
        }
        for ($i=0; $i<$steps; $i++) {
            $pos = [$pos[0] + $m[$f][0], $pos[1] + $m[$f][1]];
            $key = implode(",", $pos);
            if (!array_key_exists($key,$visited)) {
                $visited[$key] = 1;
            } else break 2;
        }
    }
    $distance = abs($pos[0])+abs($pos[1]);

    echo "Part 1: Number of blocks = $distance\n";
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
