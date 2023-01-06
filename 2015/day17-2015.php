<?php

$data = array();
$list = array();
function readInput(): void
{
    global $data;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $data[] = intval($line);
    }
    sort($data);
}

function findSums($target, $index, $sum=0, $used=array()) {
    global $data, $list;

    // add current index to sum
    $sum+=$data[$index];
    $used[$index] = 1;

    // check if we reached our target
    if ($sum == $target) {
        ksort($used);
        $key = implode(",", array_keys($used));
        if (!array_key_exists($key, $list)) {
            //echo "Adding $key\n";
            $list[$key] = 1;
            return;
        }
    }
    for ($k=$index+1; $k<count($data); $k++) {
        // skip any remaining numbers if they put us over target as they will be greater than this one
        if ($data[$k] + $sum > $target) break;
        findSums($target, $k, $sum, $used);
    }
}

function part1(): void
{
    global $data,$list;
    for ($k=0; $k<count($data); $k++) {
        findSums(150, $k);
    }
    echo "Part 1: Total combinations = ".count($list)."\n";
}

function part2(): void
{
    global $data,$list;
    foreach ($data as $k=>$d) {
        findSums(150, $k);
    }
    $containers = array();
    foreach ($list as $k=>$l) {
        $containers[] = substr_count($k,",")+1;
    }
    rsort($containers);
    $containers = array_count_values($containers);
    echo "Part 2: Number of ways to use minimal containers = ".array_pop($containers)."\n";
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
