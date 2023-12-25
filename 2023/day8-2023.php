<?php

function readInput() {
    global $dirs, $map;
    $lines = file('input.txt');
    $dirs = str_split(trim(array_shift($lines)));
    array_shift($lines);
    foreach ($lines as $line) {
        preg_match_all('/[A-Z]{3}/', $line, $matches);
        $map[$matches[0][0]] = ["L"=>$matches[0][1], "R"=>$matches[0][2]];
    }
}

function part1() {
    global $dirs, $map;
    $node = "AAA";
    $dir = 0;
    $steps = 0;
    while ($node != "ZZZ") {
        $node = $map[$node][$dirs[$dir]];
        $dir = ($dir+1)%count($dirs);  // wrap back to beginning
        $steps++;
    }
    print "Part 1: The total number of steps is $steps\n";
}

function part2() {
    global $dirs, $map;
    $dir = 0;
    foreach (array_keys($map) as $node) {
        if ($node[-1] == 'A') {
            $steps = 0;
            // after testing the output I determined that each node hits an end node and then repeats the
            // pattern over and over in the same number of steps.  Therefore, we only need to see how many steps
            // it takes to reach the first end node instead of looking for patterns
            while ($node[-1] != 'Z') {
                $node = $map[$node][$dirs[$dir]];
                $dir = ($dir+1)%count($dirs);  // wrap back to beginning
                $steps++;
            }
            $nodes[] = $steps;
        }
    }

    print "Part 2: The total number of steps is ".findlcm($nodes)."\n";
}

function gcf($a, $b)
{
    if ($b == 0)
        return $a;
    return gcf($b, $a % $b);
}

function findlcm($arr)
{
    $ans = $arr[0];
    for ($i = 1; $i < count($arr); $i++)
        $ans = ((($arr[$i] * $ans)) /
            (gcf($arr[$i], $ans)));

    return $ans;
}

readInput();
part1();
part2();
