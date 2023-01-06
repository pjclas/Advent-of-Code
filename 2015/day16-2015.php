<?php

$tape= array("children"=>3,
             "cats"=>7,
             "samoyeds"=>2,
             "pomeranians"=>3,
             "akitas"=>0,
             "vizslas"=>0,
             "goldfish"=>5,
             "trees"=>3,
             "cars"=>2,
             "perfumes"=>1);
$aunts = array();

function readInput(): void
{
    global $aunts;
    $lines = file('input.txt');
    foreach ($lines as $k=>$line) {
        $line = preg_replace('/Sue \d+: /', '', trim($line));
        $data = explode(", ", trim($line));
        foreach ($data as $d) {
            $item = explode(": ", $d);
            $aunts[$k][$item[0]] = $item[1];
        }
    }
}


function part1(): void
{
    global $aunts, $tape;
    $aunt = 0;
    foreach ($aunts as $k=>$data) {
        foreach ($data as $prop=>$num) {
            if ($tape[$prop] != $num) continue 2;
        }
        // if we got here then we matched all our properties
        $aunt = $k+1;
        break;
    }

    echo "Part 1: Aunt who sent gift is #$aunt\n";
}

function part2(): void
{
    global $aunts, $tape;
    $aunt = 0;
    foreach ($aunts as $k=>$data) {
        foreach ($data as $prop=>$num) {
            switch ($prop) {
                case "cats":
                case "trees":
                    if ($num <= $tape[$prop]) continue 3;
                    break;
                case "pomeranians":
                case "goldfish":
                if ($num >= $tape[$prop]) continue 3;
                    break;
                default:
                    if ($tape[$prop] != $num) continue 3;
                    break;
            }
        }
        // if we got here then we matched all our properties
        $aunt = $k+1;
        break;
    }

    echo "Part 2: Aunt who sent gift is #$aunt\n";
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
