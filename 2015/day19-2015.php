<?php

const INT_MAX = 9223372036854775807;

$reps = array();
$rreps = array();
$vis = array();
$formula = "";
$minCount = INT_MAX;
function readInput(): void
{
    global $reps, $rreps, $formula;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        if (str_contains($line,"=>")) {
            $data = explode(" => ", trim($line));
            $reps[$data[0]][] = $data[1];
            $rreps[$data[1]] = $data[0];
        } else if (trim($line) != "") {
            $formula = trim($line);
        }
    }
}

function reduceFormula($formula, $count=0) {
    global $rreps, $vis, $minCount;
    $min = INT_MAX;
    //echo "Looking at formula $formula\n";
    if ($count >= $minCount) return false;

    if ($formula == "e") {
        echo "Found it in $count steps\n";
        $minCount = $count;
        return $count;
    }
    if (array_key_exists($formula, $vis) && $vis[$formula] <= $count) return false;
    else $vis[$formula] = $count;

    foreach ($rreps as $r=>$mol) {
        //echo "Checking $r -> $mol\n";
        foreach (strpos_all($formula, $r) as $pos) {
            //echo "Replacing at index $pos\n";
            $c = reduceFormula(substr_replace($formula,$mol,$pos,strlen($r)), $count+1);
            if ($c !== false && $c<$min) $min = $c;
        }
    }

    return $min;
}
function strpos_all($haystack, $needle) {
    $offset = 0;
    $allpos = array();
    while (($pos = strpos($haystack, $needle, $offset)) !== false) {
        $offset   = $pos + 1;
        $allpos[] = $pos;
    }
    return $allpos;
}

function part1(): void
{
    global $reps, $formula;
    $mols = array();
    foreach ($reps as $mol=>$rs) {
        foreach ($rs as $r) {
            foreach (strpos_all($formula, $mol) as $pos) {
                $mols[] = substr_replace($formula,$r,$pos,strlen($mol));
            }
        }
    }

    echo "Part 1: Distinct molecules = ".count(array_unique($mols))."\n";
}

/*
function part2(): void
{
    global $formula, $rreps, $vis;
    $q[] = array("formula"=>$formula, "time"=>0);
    while (count($q) != 0) {
        $v = array_shift($q);
//        echo $v["formula"]."\n";
        if ($v["formula"] == "e") {
            echo $v["time"]."\n";
            return;
        }
        if (array_key_exists($v["formula"], $vis)) continue;
        else $vis[$v["formula"]] = $v["time"];

        foreach ($rreps as $r=>$mol) {
            //echo "Checking $r -> $mol\n";
            foreach (strpos_all($v["formula"], $r) as $pos) {
                //echo "Replacing at index $pos\n";
                $q[] = ["formula"=>substr_replace($v["formula"],$mol,$pos,strlen($r)), "time"=>$v["time"]+1];
            }
        }
    }


    echo "Part 2: Time to make medicine = \n";
}*/

function part2() {
    global $formula;

    $time = reduceFormula($formula);

    echo "Part 2: Time to make medicine = $time\n";
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
