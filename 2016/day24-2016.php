<?php
const INT_MAX = 9223372036854775807;

$maze = array();
$locations = array();
function readInput(): void
{
    global $maze, $locations;
    $lines = file('input.txt');
    foreach ($lines as $r=>$line) {
        $maze[$r] = str_split(trim($line));
        foreach ($maze[$r] as $c=>$spot) {
            if (is_numeric($spot)) {
                $locations[$spot] = ["coords"=>[$r,$c], "dist"=>[]];
            }
        }

    }
    ksort($locations);

    // now build shortest path array
    foreach ($locations as $loc=>$data) findPaths($loc);
}

function findPaths($start)
{
    global $maze, $locations;

    $dirs = [[1,0],[-1,0],[0,1],[0,-1]];
    $q = [["pos"=>$locations[$start]["coords"], "moves"=>0]];
    $vis[implode(",",$locations[$start]["coords"])] = 1;
    while (count($q)>0) {
        $v = array_shift($q);
        if (is_numeric($maze[$v["pos"][0]][$v["pos"][1]]) && $v["moves"] != 0) {
            $locations[$start]["dist"][$maze[$v["pos"][0]][$v["pos"][1]]] = $v["moves"];
            $locations[$maze[$v["pos"][0]][$v["pos"][1]]]["dist"][$start] = $v["moves"];
        }
        if (count($locations[$start]["dist"]) == count($locations)-1) break;
        foreach ($dirs as $dir) {
            $newLoc = [$v["pos"][0]+$dir[0],$v["pos"][1]+$dir[1]];
            // check if we can move data to this node
            if ($maze[$newLoc[0]][$newLoc[1]] != "#") {
                $key = implode(",",$newLoc);
                // check if we already tried this position
                if (!array_key_exists($key, $vis)) {
                    $vis[$key] = 1;
                    $q[] = ["pos"=>$newLoc, "moves"=>$v["moves"]+1];
                }
            }
        }
    }
}

$vis = array();
$bestDistance = INT_MAX;
function findShortestPath($return = false, $location=0, $stops=array(0), $distance=0)
{
    global $locations, $vis, $bestDistance;

    // check if we are done
    if (count($stops) == count($locations)) {
        if ($return) $distance+=$locations[$location]["dist"][0];
        if ($distance < $bestDistance) {
            $bestDistance = $distance;
        }
        return $distance;
    }
    if ($distance >= $bestDistance) return false;

    $curDist = INT_MAX;
    $key = implode(",", $stops);
    if (array_key_exists($key, $vis) && $vis[$key] <= $distance) {
        return false;
    } else {
        $vis[$key] = $distance;
    }
    foreach($locations as $loc=>$data) {
        if (!in_array($loc,$stops)) {
            // try visiting this node next
            $dist = findShortestPath($return, $loc, array_merge($stops, [$loc]), $distance+$locations[$location]["dist"][$loc]);
            if ($dist !== false && $dist<$curDist) {
                $curDist = $dist;
            }
        }
    }

    return $curDist;
}
function part1(): void
{
    echo "Part 1: Least number of moves = ".findShortestPath()."\n";
}

function part2(): void
{
    global $vis, $bestDistance;
    $vis = array();
    $bestDistance = INT_MAX;

    echo "Part 2: Least number of moves = ".findShortestPath(true)."\n";
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
