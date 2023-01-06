<?php

$row = $col = 0;
$startcode = 20151125;

function readInput(): void
{
    global $row, $col;
    $lines = file('input.txt');
    preg_match_all('!\d+!', $lines[0], $data);
    list($row, $col) = $data[0];
}

function part1(): void
{
    global $startcode, $row, $col;
    $r = $c = 1;
    $code = $startcode;
    while ($r != $row || $c != $col) {
        // find location of next code
        if ($r==1) {
            $r = $c+1;
            $c = 1;
        } else {
            $r--;
            $c++;
        }
        $code = ($code*252533)%33554393;
    }

    echo "Part 1: Code is $code\n";
}

readInput();
$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";