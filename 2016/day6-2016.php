<?php

const INT_MAX = 9223372036854775807;
const INT_MIN = -9223372036854775808;

$msgs = array();
function readInput(): void
{
    global $msgs;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $letters = str_split(trim($line));
        foreach ($letters as $k=>$letter) {
            $msgs[$k][] = $letter;
        }
    }
}


function part1(): void
{
    global $msgs;
    $msg = "";
    foreach ($msgs as $m) {
        $data = array_count_values($m);
        arsort($data);
        $msg.=array_keys($data)[0];
    }

    echo "Part 1: Message = $msg\n";
}

function part2(): void
{
    global $msgs;
    $msg = "";
    foreach ($msgs as $m) {
        $data = array_count_values($m);
        asort($data);
        $msg.=array_keys($data)[0];
    }

    echo "Part 2: Message = $msg\n";
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
