<?php

function readInput() {
    global $steps;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $steps = explode(",", trim($line));
    }
}

function runHash($s) {
    $value = 0;
    foreach (str_split($s) as $char) {
        $value+=ord($char);
        $value*=17;
        $value%=256;
    }
    return $value;
}
function part1() {
    global $steps;
    $total = 0;
    foreach ($steps as $step) {
        $total+=runHash($step);
    }
    print "Part 1: The total of hash results is $total\n";
}

function part2() {
    global $steps;
    $total = 0;
    $boxes = array();
    foreach ($steps as $step) {
        $s = explode("=", $step);
        if (count($s) != 2) $s[0] = substr($s[0],0,strlen($s[0])-1);
        $box = runHash($s[0]);
        if (count($s) != 2) {
            // remove the lens from the box
            if (isset($boxes[$box])) {
                unset($boxes[$box][$s[0]]);
            }
        } else {
            $boxes[$box][$s[0]] = $s[1];
        }
    }
    foreach ($boxes as $box=>$lenses) {
        $slot=1;
        foreach ($lenses as $fp) { // focal point
            $total += ($box + 1) * $slot * $fp;
            $slot++;
        }
    }
    print "Part 2: The total focusing power is $total\n";
}

readInput();
part1();
part2();
