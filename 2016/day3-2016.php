<?php

$input = array();
function readInput(): void
{
    global $input;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        preg_match_all('!\d+!', $line, $numbers);
        $input[] = $numbers[0];
    }
}

function isTriangle($sides)
{
    return ($sides[0]+$sides[1]>$sides[2] &&
            $sides[0]+$sides[2]>$sides[1] &&
            $sides[1]+$sides[2]>$sides[0]);
}

function part1(): void
{
    global $input;
    $triangles = 0;
    foreach ($input as $sides) {
        if (isTriangle($sides)) $triangles++;
    }

    echo "Part 1: Number of valid triangles = $triangles\n";
}

function part2(): void
{
    global $input;
    $triangles = 0;
    for ($i=0; $i<count($input)-2; $i+=3) {
        if (isTriangle([$input[$i][0],$input[$i+1][0],$input[$i+2][0]])) $triangles++;
        if (isTriangle([$input[$i][1],$input[$i+1][1],$input[$i+2][1]])) $triangles++;
        if (isTriangle([$input[$i][2],$input[$i+1][2],$input[$i+2][2]])) $triangles++;
    }

    echo "Part 2: Number of valid triangles = $triangles\n";
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
