<?php

$code = "qtetzkpl";
$dirs = [[-1, 0], [1, 0], [0, -1], [0, 1]];
$sDirs = ['U','D','L','R'];

function getDirs($passcode): array
{
    $hash = md5($passcode);
    $dirs = array();
    for ($d=0; $d<4; $d++) {
        if (preg_match('/[b-f]/', $hash[$d]))
            $dirs[] = $d;
    }

    return $dirs;
}

function findShortestPath($target, $code): string
{
    global $dirs, $sDirs;
    $q = [["code" => $code, "pos" => [0, 0]]];
    while (count($q) != 0) {
        $v = array_shift($q);
        // check if we are done
        if ($v["pos"] == $target) break;

        // check each direction for paths
        foreach (getDirs($v["code"]) as $d) {
            $newLoc = [$v["pos"][0]+$dirs[$d][0], $v["pos"][1]+$dirs[$d][1]];
            if ($newLoc[0] >= 0 && $newLoc[1] >= 0 && $newLoc[0] <= 3 && $newLoc[1] <= 3) {
                $q[] = ["code" => $v["code"].$sDirs[$d], "pos" => $newLoc];
            }
        }
    }

    return substr($v["code"],strlen($code));
}

function findLongestPath($target, $code, $pos=[0,0], $count=0): int
{
    global $dirs, $sDirs;
    $steps = 0;
    // check if we are done
    if ($pos == $target) return $count;

    // check each direction for paths
    foreach (getDirs($code) as $d) {
        $newLoc = [$pos[0]+$dirs[$d][0], $pos[1]+$dirs[$d][1]];
        if ($newLoc[0] >= 0 && $newLoc[1] >= 0 && $newLoc[0] <= 3 && $newLoc[1] <= 3) {
            $s = findLongestPath($target,$code.$sDirs[$d], $newLoc, $count+1);
            if ($s > $steps) $steps = $s;
        }
    }

    return $steps;
}


function part1(): void
{
    global $code;
    echo "Part 1: Number of steps = ".findShortestPath([3,3], $code)."\n";
}

function part2(): void
{
    global $code;
    echo "Part 2: Max number of steps = ".findLongestPath([3,3],$code)."\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
