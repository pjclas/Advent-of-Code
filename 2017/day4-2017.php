<?php

$phrases = array();
function readInput(): void
{
    global $phrases;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $phrases[] = explode(" ", trim($line));
    }
}


function part1(): void
{
    global $phrases;
    $valid = 0;
    foreach ($phrases as $phrase) {
        $vals = array_count_values($phrase);
        rsort($vals);
        if ($vals[0] == 1) $valid++;
    }

    echo "Part 1: Number of valid phrases = $valid\n";
}

function part2(): void
{
    global $phrases;
    $valid = 0;
    foreach ($phrases as $phrase) {
        $newvals = array();
        foreach ($phrase as $word) {
            $vals = str_split($word);
            sort($vals);
            $newvals[] = implode("", $vals);
        }
        $newvals = array_count_values($newvals);
        rsort($newvals);
        if ($newvals[0] == 1) $valid++;
    }

    echo "Part 2: Number of valid phrases = $valid\n";
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
