<?php

$programs = array();
function readInput(): void
{
    global $programs;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $data = explode(" -> ", trim($line));
        $program = explode(" ", $data[0]);
        preg_match('!\d+!', $program[1], $match);
        $programs[$program[0]] = ["weight"=>($match[0])];
        if (array_key_exists(1, $data)) {
            $programs[$program[0]]["children"] = explode(", ", $data[1]);
        }
    }
}

function getRoot($programs)
{
    $program = key($programs);
    do {
        $found = false;
        foreach ($programs as $prog=>$data) {
            if (array_key_exists("children", $data) && in_array($program, $data["children"])) {
                $program = $prog;
                $found = true;
                break;
            }
        }
    } while ($found);

    return $program;
}
function fixBadTowerWeight($name)
{
    global $programs;
    $weight = $programs[$name]["weight"];
    $weights = array();
    $weightLookup = array();
    if (array_key_exists("children", $programs[$name])) {
        foreach ($programs[$name]["children"]as $child) {
            $w = fixBadTowerWeight($child);
            if (!is_numeric($w)) return $w;
            $weights[$child] = $w;
            $weightLookup[$w] = $child;
        }
        $counts = array_count_values($weights);
        if (count($counts) != 1) {
            arsort($counts);
            $counts = array_keys($counts);
            $child = $weightLookup[$counts[1]];
            $programs[$child]["weight"]+=$counts[0] - $counts[1];
            return $child;
        }
    }
    return $weight + array_sum($weights);
}
function part1(): void
{
    global $programs;
    echo "Part 1: Bottom program = ".getRoot($programs)."\n";
}

function part2(): void
{
    global $programs;
    $program = fixBadTowerWeight(getRoot($programs));
    echo "Part 2: Program $program should weigh ".$programs[$program]["weight"]."\n";
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
