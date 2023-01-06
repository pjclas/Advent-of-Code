<?php

const INT_MAX = 9223372036854775807;

$weapons = [["cost"=>8, "damage"=>4, "armor"=>0],
            ["cost"=>10, "damage"=>5, "armor"=>0],
            ["cost"=>25, "damage"=>6, "armor"=>0],
            ["cost"=>40, "damage"=>7, "armor"=>0],
            ["cost"=>74, "damage"=>8, "armor"=>0]];
$armors = [["cost"=>0,  "damage"=>0, "armor"=>0],  // line handles optional case
           ["cost"=>13,  "damage"=>0, "armor"=>1],
           ["cost"=>31,  "damage"=>0, "armor"=>2],
           ["cost"=>53,  "damage"=>0, "armor"=>3],
           ["cost"=>75,  "damage"=>0, "armor"=>4],
           ["cost"=>102, "damage"=>0, "armor"=>5]];
$rings = [["cost"=>0,  "damage"=>0, "armor"=>0],   // line handles optional case
          ["cost"=>25,  "damage"=>1, "armor"=>0],
          ["cost"=>50,  "damage"=>2, "armor"=>0],
          ["cost"=>100, "damage"=>3, "armor"=>0],
          ["cost"=>20,  "damage"=>0, "armor"=>1],
          ["cost"=>40,  "damage"=>0, "armor"=>2],
          ["cost"=>80,  "damage"=>0, "armor"=>3]];
$hp = $damage = $armor = 0;

function readInput(): void
{
    global $hp, $damage, $armor;
    $lines = file('input.txt');
    $hp = explode(": ", trim($lines[0]))[1];
    $damage = explode(": ", trim($lines[1]))[1];
    $armor = explode(": ", trim($lines[2]))[1];
}

function doBattle($w, $a, $rings)
{
    global $hp, $damage, $armor;
    $ra = $rd = 0;
    foreach ($rings as $r) {
        $ra += $r["armor"];
        $rd += $r["damage"];
    }
    $pd = (($w["damage"]+$rd)-$armor>0)?$w["damage"]+$rd-$armor:1;
    $pt = ceil($hp/$pd);
    $bd = ($damage-($a["armor"]+$ra)>0)?$damage-($a["armor"]+$ra):1;
    $bt = ceil(100/$bd);

    return $pt<$bt+1;
}

function checkGear($win = true)
{
    global $weapons, $armors, $rings;

    // check if we want cheapest to win or most expensive to lose
    if ($win) $gold = INT_MAX;
    else $gold = 0;

    foreach ($weapons as $w) {
        foreach ($armors as $a) {
            $cost = 0;
            // check fight results with no rings
            $result = doBattle($w, $a, array());
            if ($result === $win) {
                $cost += $w["cost"] + $a["cost"];
                if ($win && $cost < $gold) $gold = $cost;
                else if (!$win && $cost > $gold) $gold = $cost;
            }
            // now add 1 or 2 rings (1 ring represented in array with 0 stats for second ring)
            for ($k=0; $k<count($rings)-1; $k++) {
                $myRings[0] = $rings[$k];
                for ($j=$k+1; $j<count($rings); $j++) {
                    $myRings[1] = $rings[$j];
                    $cost = $rings[$k]["cost"] + $rings[$j]["cost"];

                    // check fight results
                    $result = doBattle($w, $a, $myRings);
                    if ($result === $win) {
                        $cost += $w["cost"] + $a["cost"];
                        if ($win && $cost < $gold) $gold = $cost;
                        else if (!$win && $cost > $gold) $gold = $cost;
                    }
                }
            }
        }
    }
    return $gold;
}
function part1(): void
{
    $gold = checkGear();
    echo "Part 1: Least gold is $gold\n";
}

function part2(): void
{
    $gold = checkGear(false);
    echo "Part 2: Most gold is $gold\n";
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
