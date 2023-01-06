<?php

const INT_MAX = 9223372036854775807;

$presents = array();
$leastPresents = INT_MAX;
$lowestEntanglement = INT_MAX;

function readInput(): void
{
    global $presents;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $presents[] = intval($line);
    }
    rsort($presents);
}

function findSum($target, $total=0, $vals=array())
{
    global $presents, $leastPresents, $lowestEntanglement;
    $qe = INT_MAX;
    if ($total == $target) {
        if (count($vals) <= $leastPresents) {
            $leastPresents = count($vals);
            $q = 1;
            foreach ($vals as $v) $q*=$presents[$v];
            return $q;
        }
        else return false;
    }
    if ($total > $target || count($vals) == $leastPresents) return false;

    if (count($vals) > 0)
        $nextIndex = $vals[count($vals)-1]+1;
    else $nextIndex = 0;

    for ($i=$nextIndex; $i<count($presents); $i++) {
        $nextVals = $vals;
        $nextVals[] = $i;
        $ret = findSum($target, $total+$presents[$i], $nextVals);
        if ($ret !== false && $qe>$ret) {
            $qe = $ret;
        }
    }

    return $qe;
}

function part1(): void
{
    global $presents;
    $qe = findSum(array_sum($presents)/4);

    echo "Part 1: Quantum entanglement = $qe\n";
}

function part2(): void
{

    echo "Part 2: \n";
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
