<?php

$games = array();

function readInput() {
    global $games;
    $lines = file('input.txt');
    $x=1;
    foreach ($lines as $line) {
        $line = preg_replace('/Game (\d+): /', '', $line);
        $sets = explode(";", $line);
        foreach ($sets as $set) {
            preg_match_all('/(\d+) ([a-z]+)/', $set, $cubes);
            $c = array();
            for ($y=0; $y<count($cubes[2]); $y++) {
                $c[$cubes[2][$y]] = $cubes[1][$y];
            }
            $games[$x][] = $c;
        }
        $x++;
    }
}

function part1() {
    global $games;
    $cubeCount = ["red"=>12, "green"=>13, "blue"=>14];
    $total = 0;

    foreach ($games as $gameNum=>$rounds) {
        foreach ($rounds as $round) {
            if ((isset($round["red"]) && $round["red"]>$cubeCount["red"]) ||
                (isset($round["green"]) && $round["green"]>$cubeCount["green"]) ||
                (isset($round["blue"]) && $round["blue"]>$cubeCount["blue"])) {
                continue 2;
            }
        }
        $total += $gameNum;
    }

    print "Part 1: The sum of IDs of possible games is $total\n";
}

function part2() {
    global $games;
    $total = 0;

    foreach ($games as $gameNum=>$rounds) {
        $r=$g=$b = 0;
        foreach ($rounds as $round) {
            if (isset($round["red"]) && $round["red"] > $r) $r = $round["red"];
            if (isset($round["blue"]) && $round["blue"] > $b) $b = $round["blue"];
            if (isset($round["green"]) && $round["green"] > $g) $g = $round["green"];
        }
        $total += $r*$g*$b;
    }

    print "Part 2: The sum of power of all the games is $total\n";
}

readInput();
part1();
part2();

?>
