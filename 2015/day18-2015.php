<?php

$lights = array();
function readInput(): void
{
    global $lights;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $lights[] = str_split(str_replace([".","#"],[0,1], trim($line)));
    }
}

function animate($lights)
{
    $dirs = [[-1,-1],[-1,0],[-1,1],[0,-1],[0,1],[1,-1],[1,0],[1,1]];
    $next = array();
    foreach ($lights as $r => $cols) {
        foreach ($cols as $c => $d) {
            $count = 0;
            foreach ($dirs as $dir) {
                $count += $lights[$r + $dir[0]][$c + $dir[1]]??0;
            }
            if ($d == 1) {
                if ($count == 2 || $count == 3) {
                    $next[$r][$c] = 1;
                } else {
                    $next[$r][$c] = 0;
                }
            } else {
                if ($count == 3) {
                    $next[$r][$c] = 1;
                } else {
                    $next[$r][$c] = 0;
                }
            }
        }
    }

    return $next;
}

function part1($lights): void
{
    for ($i=0; $i<100; $i++) {
        $lights = animate($lights);
    }
    $sum=0;
    foreach ($lights as $row) {
        $sum+=array_sum($row);
    }
    echo "Part 1: Number of lights = $sum\n";
}

function part2($lights): void
{
    for ($i=0; $i<100; $i++) {
        $lights = animate($lights);
        $lights[0][0] = 1;
        $lights[count($lights)-1][0] = 1;
        $lights[count($lights)-1][count($lights[0])-1] = 1;
        $lights[0][count($lights[0])-1] = 1;
    }
    $sum=0;
    foreach ($lights as $row) {
        $sum+=array_sum($row);
    }
    echo "Part 2: Number of lights = $sum\n";
}

readInput();
$start = microtime(true);
part1($lights);
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2($lights);
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
