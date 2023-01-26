<?php

$jumps = array();
function readInput(): void
{
    global $jumps;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $jumps[] = intval(trim($line));
    }
}


function part1($jumps): void
{
    $i=0;
    $steps = 0;
    do {
        $jumps[$i]++;
        $i+=$jumps[$i]-1;
        $steps++;
    } while ($i>=0 && $i<count($jumps));

    echo "Part 1: Number of steps to reach exit = $steps\n";
}

function part2($jumps): void
{
    $i=0;
    $steps = 0;
    do {
        $j = $i;
        if ($jumps[$i]>=3) $offset = -1;
        else $offset = 1;
        $i+=$jumps[$i];
        $jumps[$j]+=$offset;
        $steps++;
    } while ($i>=0 && $i<count($jumps));

    echo "Part 2: Number of steps to reach exit = $steps\n";
}

readInput();
$start = microtime(true);
part1($jumps);
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2($jumps);
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
