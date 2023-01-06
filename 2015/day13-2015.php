<?php

define("INT_MAX", 2147483647);
define("INT_MIN", -2147483648);

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);

$h = array();

function readInput() {
    global $h;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $parts = explode(" ", substr($line, 0, strlen($line)-2));
        $h[$parts[0]][$parts[10]] = ($parts[2] == "gain")?$parts[3]:-1*$parts[3];
    }
}

function getHappiness($n, $seats = array()) {
    global $h;
    $seats[] = $n;
    $hTotal = INT_MIN;
    foreach ($h[$n] as $f=>$val) {
        // check if this person is already seated
        if (!in_array($f, $seats)) {
            $hs = $h[$f][$n] + $val;
            $hTotal = max($hTotal, $hs + getHappiness($f, $seats));
        }
    }
    if ($hTotal == INT_MIN) {
        // everyone is seated at the table, add the last values
        $hTotal = $h[$n][$seats[0]] + $h[$seats[0]][$n];
    }

    return $hTotal;
}

function part1() {
    global $h;
    reset($h);
    $happy = getHappiness(key($h));

    echo "Part 1: Happiness = $happy\n";
}

function part2() {
    global $h;
    foreach (array_keys($h) as $n) {
        $h[$n]["me"] = 0;
        $h["me"][$n] = 0;
    }
    reset($h);
    $happy = getHappiness(key($h));

    echo "Part 2: Happiness = $happy\n";
}

readInput();

part1();
part2();

?>
