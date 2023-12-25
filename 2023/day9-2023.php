<?php

function readInput() {
    global $hist;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        preg_match_all('/-?\d+/', $line, $matches);
        $hist[] = ["data"=>$matches[0], "ends"=>array(end($matches[0]))];
    }
}

function getPrevDigit($data) {
    return getDigit($data, true);
}

function getNextDigit($data) {
    return getDigit($data);
}
function getDigit($data, $first=false) {
    $newData = array();
    $allZero = true;
    for ($i = 0; $i < count($data)-1; $i++) {
        $diff = $data[$i+1] - $data[$i];
        $newData[] = $diff;
        if ($diff != 0) $allZero = false;
    }
    if (!$allZero) return ($first) ? $data[0]-getDigit($newData, true) : end($data)+getNextDigit($newData);
    else return end($data);
}
function part1() {
    global $hist;
    $total = 0;
    foreach ($hist as $h) {
        $total += getNextDigit($h["data"]);
    }

    print "Part 1: The sum of next values is $total\n";
}

function part2() {
    global $hist;
    $total = 0;
    foreach ($hist as $h) {
        $total += getPrevDigit($h["data"]);
    }

    print "Part 2: The sum of previous values is $total\n";
}

readInput();
part1();
part2();
