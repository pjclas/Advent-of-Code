<?php

$input = array();
function readInput(): void
{
    global $input;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $input[] = str_split(trim($line));
    }
}

function getCode($keypad, $start) {
    global $input;
    $key = $start;
    $code = "";
    $end = count($keypad);
    foreach ($input as $line) {
        foreach ($line as $i) {
            switch ($i) {
                case "U":
                    if ($key[0] > 0 && !empty($keypad[$key[0]-1][$key[1]])) $key[0]--;
                    break;
                case "D":
                    if ($key[0] < $end && !empty($keypad[$key[0]+1][$key[1]])) $key[0]++;
                    break;
                case "L":
                    if ($key[1] > 0 && !empty($keypad[$key[0]][$key[1]-1])) $key[1]--;
                    break;
                case "R":
                    if ($key[1] < $end && !empty($keypad[$key[0]][$key[1]+1])) $key[1]++;
                    break;
            }
        }
        $code.=$keypad[$key[0]][$key[1]];
    }

    return $code;
}

function part1(): void
{
    $code = getCode([[1,2,3],[4,5,6],[7,8,9]], [1,1]);

    echo "Part 1: Code = $code\n";
}

function part2(): void
{
    $code = getCode([[0,0,1,0,0],[0,2,3,4,0],[5,6,7,8,9],[0,"A","B","C",0],[0,0,"D",0,0]], [2,0]);

    echo "Part 2: Code = $code\n";
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
