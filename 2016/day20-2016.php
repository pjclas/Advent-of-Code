<?php

$blocked = array();
function readInput(): void
{
    global $blocked;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        list($start, $end) = explode("-", trim($line));
        $blocked[$start] = $end;
    }
}

function part1(): void
{
    global $blocked;
    ksort($blocked);
    $ip = 0;
    foreach ($blocked as $s=>$e) {
        if ($ip>=$s) {
            if ($ip <= $e) $ip = $e+1;
        } else break;
    }

    echo "Part 1: Lowest valid ip = $ip\n";
}

function part2(): void
{
    global $blocked;
    $max = 4294967295;
    ksort($blocked);
    $ip = 0;
    $ips = 0;
    foreach ($blocked as $s=>$e) {
        if ($ip>=$s) {
            if ($ip <= $e) $ip = $e+1;
        } else {
            $ips+=$s-$ip;
            $ip = $e+1;
        }
    }
    if ($max >= $ip)
        $ips+=$max-$ip+1;

    echo "Part 2: Number of valid ips = $ips\n";
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
