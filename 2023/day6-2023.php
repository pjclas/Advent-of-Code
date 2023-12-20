<?php

$records = array();

function readInput() {
    global $records;
    $lines = file('input.txt');
    preg_match_all('/\d+/', $lines[0], $time);
    preg_match_all('/\d+/', $lines[1], $dist);
    foreach ($time[0] as $key=>$time) {
        $records[$time] = ["dist"=>$dist[0][$key], "wins"=>0];
    }
}

function part1() {
    global $records;

    $times = array_keys($records); 
    rsort($times);
    $max = $times[0];
    for ($press=1; $press<$max; $press++) {
        foreach ($records as $time=>$rec) {
            if ($time > $press) {
                $dist = ($time-$press) * $press;
                if ($dist>$records[$time]["dist"])
                    $records[$time]["wins"]++;
            }
        }
    }
    $total = 1;
    foreach ($records as $rec)
        $total = $total*$rec["wins"];

    print "Part 1: The product of number of ways to win across races is $total\n";
}

function part2() {
    global $records;
    $time = "";
    $dist = "";
    $wins = 0;
    foreach($records as $t=>$rec) {
        $time.=$t;
        $dist.=$rec["dist"];
    }
    for ($press=1; $press<(int)$time; $press++) {
        $d = ((int)$time-$press) * $press;
        if ($d>$dist)
            $wins++;
    }
    print "Part 2: The number of ways we can win the race is $wins\n";
}

readInput();
part1();
part2();

?>
