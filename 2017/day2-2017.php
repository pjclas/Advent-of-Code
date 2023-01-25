<?php

$matrix = array();
function readInput(): void
{
    global $matrix;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        preg_match_all('!\d+!', $line, $data);
        $matrix[] = $data[0];
    }
}

function part1(): void
{
    global $matrix;
    $checksum = 0;
    foreach ($matrix as $line) {
        sort($line);
        $checksum += $line[count($line)-1] - $line[0];
    }

    echo "Part 1: Checksum = $checksum\n";
}

function part2(): void
{
    global $matrix;
    $checksum = 0;
    foreach ($matrix as $line) {
        rsort($line);
        for ($i=0; $i<count($line)-1; $i++)
            for ($j=$i+1; $j<count($line); $j++) {
                if ($line[$i] % $line[$j] == 0) {
                    $checksum+= $line[$i] / $line[$j];
                    break;
                }
            }
    }

    echo "Part 2: Checksum = $checksum\n";
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
