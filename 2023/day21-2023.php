<?php

function readInput() {
    global $garden, $start;
    $lines = file('input.txt');
    foreach ($lines as $row => $line) {
        $spots = str_split(trim($line));
        $pos = array_search('S', $spots);
        if ($pos !== false) $start = [$row, $pos];
        $garden[] = $spots;
    }
}

function walk($garden, $start, $steps) {
    $seen = [implode(",", $start) => 1];
    $dirs = [[1,0],[-1,0],[0,1],[0,-1]];
    $q = [[$start[0],$start[1],0]];
    while ($q) {
        list($r,$c, $step) = array_shift($q);
        if ($step == $steps) continue;  // we are done
        foreach ($dirs as $d) {
            $nextR = $r+$d[0];
            $nextC = $c+$d[1];
            if (isset($garden[$nextR][$nextC]) && $garden[$nextR][$nextC] != "#" &&
                (!array_key_exists("$nextR,$nextC", $seen))) {
                $seen["$nextR,$nextC"] = 1;
                $q[] = [$nextR, $nextC, $step + 1];
            }
        }
    }
    $posCount=0;
    $even=!($steps%2);
    foreach (array_keys($seen) as $pos) {
        list($r,$c) = explode(",", $pos);
        if (!$even && (abs($r-$start[0]) + abs($c-$start[1]) + 2) % 2) $posCount++;
        elseif ($even && !((abs($r-$start[0]) + abs($c-$start[1]) + 2) % 2)) {
            $posCount++;
        }
    }
    return $posCount;
}

function part1() {
    global $garden, $start;
    $total = walk($garden, $start, 64);
    print "Part 1: The total garden plots he can reach is $total\n";
}

function part2()
{
    global $garden, $start;
    $height = count($garden);
    $middle = intdiv($height,2);

    // The pattern has a clear inner diamond and then 4 corners that will make up an outer
    // diamond when the pattern repeats.  The pattern also has an unobstructed path through
    // the center horizontally and vertically and the start is always in the center.  Had this
    // diamond pattern not existed we could've also calculated how many steps it would take to
    // get to the edges and then calculate the different edge cases based on our steps remaining.
    $d1odd = walk($garden, $start, $middle); // get odd spaces for inner diamond
    $d1even = walk($garden, $start, $middle-1)+1;  // get even spaces for inner diamond
    $starts = [[0,0],[$height-1,0],[0,$height-1],[$height-1,$height-1]];
    $d2odd=$d2even=0;
    foreach ($starts as list($r,$c)) {
        $d2odd += walk($garden, [$r,$c], $middle); // get odd spaces for outer diamond
        $d2even += walk($garden, [$r,$c], $middle-1);  // get even spaces for outer diamond
    }
    $numd1odd=1;  // start with center diamond as a given
    $numd1even=$numd2=0;
    $steps = 26501365;
    // calculate number of inner and outer diamonds
    for ($i=1; $i<=intdiv($steps,$height); $i++) {
        if ($i%2 == 0) $numd1odd+=4*$i;
        else $numd1even+=4*$i;
        $numd2+=4*$i;
    }

    // outer diamonds are always split evenly
    $numd2even=$numd2odd=$numd2/2;
    $total = $numd1odd*$d1odd+$numd1even*$d1even+$numd2odd*$d2odd+$numd2even*$d2even;
    // subtract overlapping border and corners from inner and outer odd diamonds
    $total -= $middle*4*$numd2odd+4*$numd2odd;

    // my input had a plot in the top center area that was 1 step shy of being reached with the
    // remainder of what was left in the number of steps given if we reached this square on an
    // even rotation.  Howeber, it turns out that we reach this on an odd rotation so this square became
    // irrelevant.  I left potential this calculation in here just for completeness.
    //    $total-=2;

    print "Part 2: The total garden plots he can reach is $total\n";
}

readInput();
part1();
part2();
