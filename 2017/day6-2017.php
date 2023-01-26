<?php

$input = "11	11	13	7	0	15	5	5	4	4	1	1	7	1	15	11";
$blocks = array();
function readInput(): void
{
    global $input, $blocks;
    preg_match_all('!\d+!', $input, $data);
    $blocks = $data[0];
}

function reallocate(&$blocks)
{
    $seen = array();
    while (!array_key_exists(implode(",", $blocks), $seen)) {
        $sorted = $blocks;
        rsort($sorted);
        $high = $sorted[0];
        $seen[implode(",", $blocks)] = 1;
        $index = array_search($high, $blocks);
        $blocks[$index] = 0;
        for ($i=1; $i<=$high; $i++)
            $blocks[($index + $i) % count($blocks)]++;
    }

    return $seen;
}
function part1($blocks): void
{
    echo "Part 1: Redistribution cycles = ".count(reallocate($blocks))."\n";
}

function part2($blocks): void
{
    $keys = array_keys(reallocate($blocks));
    echo "Part 2: Number of cycles = ".(count($keys)-array_search(implode(",", $blocks), $keys))."\n";
}

readInput();
$start = microtime(true);
part1($blocks);
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2($blocks);
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
