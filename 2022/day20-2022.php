<?php

$code = array();

function readInput(): void
{
    global $code, $start;
    $lines = file('input.txt');
    $o = 0;
    foreach ($lines as $line) {
        $o++;
        $l = trim($line);
        $code[] = ["num"=>$l, "order"=>$o, "count"=>0];
        if ($l == 0) {
            $start = ["num"=>0, "order"=>$o, "count"=>1];
        }
    }
}

function findNextKey($code, $o) {
    foreach ($code as $k=>$v) {
        if ($v["order"] == $o) return $k;
    }
    return false;
}

function mixCode($code, $count) {
    for ($c=1; $c<=$count; $c++) {
        $o = 1;
        do {
            $k = findNextKey($code, $o);
            $v = $code[$k];
            if ($k !== false) {
                // move this item in the array
                unset($code[$k]);
                $nk = $k + $v["num"];
                if ($nk < 0 || $nk > count($code)) {
                    $nk %= count($code);
                }
                array_splice($code, $nk, 0, [["num" => $v["num"], "order" => $v["order"], "count" => $c]]);

            }
            $o++;
        } while ($k !== false);
    }

    return $code;
}

function part1($code): void
{
    global $start;

    $code = mixCode($code, 1);

    // now find the coordinates
    $coords = array();
    $s = array_search($start, $code);
    for($x=1000; $x<=3000; $x+=1000) {
        $coords[] = $code[($s+$x)%count($code)]["num"];
    }
    echo "Part 1: Sum = ".array_sum($coords)."\n";
}

function part2($code): void
{
    global $start;

    $count = 10;
    // add the encryption multiplier
    foreach ($code as $k=>$c) {
        $code[$k]["num"]*=811589153;
    }
    $code = mixCode($code, $count);

    $coords = array();
    $start["count"] = $count;
    $s = array_search($start, $code);
    for($x=1000; $x<=3000; $x+=1000) {
        $coords[] = $code[($s+$x)%count($code)]["num"];
    }
    echo "Part 2: Sum = ".array_sum($coords)."\n";
}

readInput();
part1($code);
part2($code);


