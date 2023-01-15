<?php

$discs = array();
function readInput(): void
{
    global $discs;
    $lines = file('input.txt');
    foreach ($lines as $k=>$line) {
        preg_match_all('!\d+!', $line, $matches);
        $discs[$k] = ["positions"=>$matches[0][1], "pos"=>$matches[0][3]];
    }
}

function findDropTime($discs): int
{
    $time = $discs[0]["positions"] - 1 - $discs[0]["pos"];
    do {
        $done = true;
        for ($d=1; $d<count($discs); $d++) {
            if (($discs[$d]["pos"]+$time+$d+1) % $discs[$d]["positions"] != 0) {
                $done = false;
                break;
            }
        }
        if (!$done) {
            // try the next window that synchronizes the hole for starting disc
            $time += $discs[0]["positions"];
        }
    } while (!$done);

    return $time;
}
function part1(): void
{
    global $discs;
    $time = findDropTime($discs);

    echo "Part 1: Drop time = $time\n";
}

function part2(): void
{
    global $discs;

    $discs[] = ["positions"=>11, "pos"=>0];
    $time = findDropTime($discs);

    echo "Part 2: Drop time = $time\n";
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
