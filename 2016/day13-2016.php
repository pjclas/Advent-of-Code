<?php

const INT_MAX = 9223372036854775807;
const INT_MIN = -9223372036854775808;

$seed = 1362;
$maze = array();
function buildMaze($x, $y): string
{
    global $seed;
    return substr_count(decbin($x*$x + 3*$x + 2*$x*$y + $y + $y*$y + $seed), "1") % 2 == 0 ? "." : "#";
}

function findPath($target)
{
    global $maze;
    $q = [["moves" => 0, "pos" => [1, 1]]];
    $vis["1,1"] = 1;
    $dirs = [[1, 0], [-1, 0], [0, 1], [0, -1]];
    while (count($q) != 0) {
        $v = array_shift($q);
        // check if we are done
        if (is_array($target)) {
            if ($v["pos"] == $target) break;
        } else {
            if ($v["moves"] == $target) break;
        }

        // check each direction for paths
        foreach ($dirs as $d) {
            $newLoc = [$v["pos"][0]+$d[0], $v["pos"][1]+$d[1]];
            if ($newLoc[0] < 0 || $newLoc[1] < 0) continue;
            if (!array_key_exists($newLoc[0], $maze) || !array_key_exists($newLoc[1], $maze[$newLoc[0]]))
                $maze[$newLoc[0]][$newLoc[1]] = buildMaze($newLoc[1], $newLoc[0]);
            if (!array_key_exists($newLoc[0].",".$newLoc[1], $vis) && $maze[$newLoc[0]][$newLoc[1]] != "#") {
                $q[] = ["moves" => $v["moves"] + 1, "pos" => $newLoc];
                $vis[$newLoc[0].",".$newLoc[1]] = 1;
            }
        }
    }
    if (is_array($target))
        return $v["moves"];
    else
        return count($vis);
}
function part1(): void
{
    $steps = findPath([39,31]);
    echo "Part 1: Number of steps = $steps\n";
}

function part2(): void
{
    $count = findPath(50);
    echo "Part 2: Number of locations = $count\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
