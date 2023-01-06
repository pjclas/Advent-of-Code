<?php

const INT_MAX = 9223372036854775807;
const INT_MIN = -9223372036854775808;

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);

function readInput(): void
{
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $cubes[] = explode(",", trim($line));
    }
}


function part1(): void
{

    echo "Part 1: \n";
}

function part2(): void
{

    echo "Part 2: \n";
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
