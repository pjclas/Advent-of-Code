<?php

$ops = array();
function readInput(): void
{
    global $ops;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $ops[] = explode(" ", trim($line));
    }
}

function swap($pw, $a, $b)
{
    $t = $pw[$a];
    $pw[$a] = $pw[$b];
    $pw[$b] = $t;
    return $pw;
}

function rotateRight($pw, $count)
{
    for ($i=0; $i<$count; $i++)
        array_unshift($pw,array_pop($pw));
    return $pw;
}

function rotateLeft($pw, $count)
{
    for ($i=0; $i<$count; $i++)
        $pw[] = array_shift($pw);
    return $pw;
}

function part1(): void
{
    global $ops;
    $pw = str_split("abcdefgh");
    foreach ($ops as $op) {
        switch ($op[0]) {
            case "swap":
                if ($op[1] == "position") {
                    $pw = swap($pw, $op[2], $op[5]);
                } else {
                    $pw = swap($pw, array_search($op[2],$pw), array_search($op[5],$pw));
                }
                break;
            case "rotate":
                if ($op[1] == "based") {
                    $count = array_search($op[6],$pw);
                    if ($count>=4) $count+=2;
                    else $count++;
                    $pw = rotateRight($pw, $count);
                } else {
                    if ($op[1] == "right")
                        $pw = rotateRight($pw, $op[2]);
                    else
                        $pw = rotateLeft($pw, $op[2]);
                }
                break;
            case "reverse":
                for ($i=$op[2],$j=$op[4]; $i<round(($op[2]+$op[4])/2); $i++, $j--)
                    $pw = swap($pw, $i, $j);
                break;
            case "move":
                $val = $pw[$op[2]];
                unset($pw[$op[2]]);
                array_splice($pw, $op[5], 0, $val);
                break;
        }
    }

    echo "Part 1: Password = ".implode("", $pw)."\n";
}

function part2(): void
{
    global $ops;
    $pw = str_split("fbgdceah");
    foreach (array_reverse($ops) as $op) {
        switch ($op[0]) {
            case "swap":
                if ($op[1] == "position") {
                    $pw = swap($pw, $op[2], $op[5]);
                } else {
                    $pw = swap($pw, array_search($op[2],$pw), array_search($op[5],$pw));
                }
                break;
            case "rotate":
                if ($op[1] == "based") {
                    for ($i=1; $i<=count($pw); $i++) {
                        $temppw = rotateLeft($pw, $i);
                        $count = array_search($op[6],$temppw);
                        if ($count>=4) $count+=2;
                        else $count++;
                        if (rotateRight($temppw, $count) == $pw) break;
                    }
                    $pw = $temppw;
                } else {
                    if ($op[1] == "right")
                        $pw = rotateLeft($pw, $op[2]);
                    else
                        $pw = rotateRight($pw, $op[2]);
                }
                break;
            case "reverse":
                for ($i=$op[2],$j=$op[4]; $i<round(($op[2]+$op[4])/2); $i++, $j--)
                    $pw = swap($pw, $i, $j);
                break;
            case "move":
                $val = $pw[$op[5]];
                unset($pw[$op[5]]);
                array_splice($pw, $op[2], 0, $val);
                break;
        }
    }

    echo "Part 2: Password = ".implode("", $pw)."\n";
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
