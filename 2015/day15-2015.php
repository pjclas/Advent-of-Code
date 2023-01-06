<?php

$ingredients = array();
$ingKeys = array();

function readInput(): void
{
    global $ingredients, $ingKeys;
    // Sugar: capacity 3, durability 0, flavor 0, texture -3, calories 2
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $data = explode(" ", trim($line));
        $ingredients[substr($data[0],0,-1)] = [intval($data[2]), intval($data[4]), intval($data[6]), intval($data[8]), intval($data[10])];
    }
    $ingKeys = array_keys($ingredients);
}

function getScore($cals=0,$ing=0,$quan=100,$amount=array()) {
    global $ingredients, $ingKeys;
    $max = 0;
    if ($ing == count($ingredients)-1) {
        // this is the last ingredient, amount is a single choice at this point so return the score
        $amount[$ingKeys[$ing]] = $quan;
        $totScore = 1;
        for ($p=0; $p<5; $p++) {
            $score = 0;
            foreach ($amount as $i => $q) {
                $score += $q*$ingredients[$i][$p];
            }
            if ($score <= 0) return 0;
            else {
                if ($p<4) {
                    $totScore *= $score;
                // check if we have a calorie goal
                } else if ($cals != 0 && $score != $cals) return 0;
            }
        }
        return $totScore;
    }

    // try each amount up to quantity
    for ($i=0; $i<=$quan; $i++) {
        $amount[$ingKeys[$ing]] = $i;
        $m = getScore($cals,$ing+1, $quan-$i, $amount);
        if ($m > $max) $max = $m;
    }

    return $max;
}
function part1(): void
{
    $score = getScore();

    echo "Part 1: Max score=$score \n";
}

function part2(): void
{
    $score = getScore(500);

    echo "Part 2: Max score=$score \n";
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
