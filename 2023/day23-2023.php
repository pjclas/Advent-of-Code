<?php

$dirs = [[1,0],[-1,0],[0,1],[0,-1]];
$slopes = ["v","^",">","<"];

function readInput():void {
    global $map, $start, $end;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $map[] = str_split(trim($line));
    }
    $start = [0,array_search(".", $map[0])];
    $end = [count($map)-1,array_search(".", end($map))];
}

// use a BFS to find longest path with directed graph
// optimize by eliminating path that reached a node earlier than current path
// runs significantly faster than DFS
function findLongestPath($map, $start, $end) : int {
    $dirs = [[1,0],[-1,0],[0,1],[0,-1]];
    $slope_dirs = ["v","^",">","<"];
    $s = implode(",", $start);
    $seen[$s] = 1;
    $mostSteps = 0;
    $q[] = [$start, [-1,$start[1]], 0, "($s)"];
    while ($q) {
        list($pos, $parent, $count, $path) = array_shift($q);
        if ($pos == $end) {
            $mostSteps = $count;
            continue;
        }
        foreach ($dirs as $i=>$d) {
            $nextPos = [$pos[0]+$d[0], $pos[1]+$d[1]];
            list ($r, $c) = $nextPos;
            if ($nextPos != $parent && ($map[$r][$c] == "." || $map[$r][$c] == $slope_dirs[$i])) {
                if (array_key_exists("$r,$c", $seen)) {
                    if (str_contains($path, "($r,$c)")) continue;  // this path has been here before

                    // we got here at a later point so let's remove the prior instance from our queue
                    foreach ($q as $k=>$p) {
                        if (str_contains($p[3], "($r,$c)")) {
                            unset($q[$k]);
                            break;
                        }
                    }
                } else $seen["$r,$c"] = 1;
                $q[] = [$nextPos, $pos, $count+1, "$path-($r,$c)"];
            }
        }
    }
    return $mostSteps;
}

// run DFS on weighted graph created with getIntersections
function findLongestPath2($nodes, $node="start", $parent="", $totalDist = 0, $seen = array()) : int {
    $maxDist=0;
    if ($node == "end") return $totalDist;

    $seen[$node] = 1;
    foreach ($nodes[$node] as list($nextNode, $dist)) {
        if (!array_key_exists($nextNode, $seen)) {
            $d = findLongestPath2($nodes, $nextNode, $node,$totalDist+$dist, $seen);
            if ($d>$maxDist) $maxDist = $d;
        }
    }
    return $maxDist;
}

function getIntersections($pos, $prevNode="start", $dist=0, $nodes = array(), &$seen = array()) : array {
    global $map, $end, $dirs;
    $s = implode(",", $pos);
    $seen[$s] = 1;
    if ($pos == $end) {
        $nodes[$prevNode][] = ["end", $dist];
        return $nodes;
    }
    $degrees=0;
    $addq = array();
    foreach ($dirs as $d) {
        $nextPos = [$pos[0] + $d[0], $pos[1] + $d[1]];
        list ($r, $c) = $nextPos;
        if (isset($map[$r][$c]) && $map[$r][$c] != "#") {
            $degrees++;
            $key = "$r,$c";
            if (!array_key_exists($key, $seen) || (array_key_exists($key, $nodes) && $key != $prevNode)) {
                $addq[] = $nextPos;
            }
        }
    }
    if ($degrees>2) {
        // this is a new node, create linkage between two nodes
        $curNode = implode(",", $pos);
        $nodes[$prevNode][] = [$curNode, $dist];
        $nodes[$curNode][] = [$prevNode, $dist];
        $dist = 0;  // reset distance
        $prevNode = $curNode;
    }
    foreach ($addq as $nextPos) {
        $nodes = getIntersections($nextPos, $prevNode, $dist+1, $nodes, $seen);
    }
    return $nodes;
}

function part1():void {
    global $map, $start, $end;
    $steps = findLongestPath($map,$start,$end);
    print "Part 1: The number of step on the longest path is $steps\n";
}

function part2():void {
    global $start;
    $nodes = getIntersections($start);
    $steps = findLongestPath2($nodes);
    print "Part 2: The number of step on the longest path is $steps\n";
}

readInput();
$starttime = microtime(true);
part1();
$starttime2 = microtime(true);
$time_elapsed_secs =  $starttime2 - $starttime;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $starttime2;
echo "Time: $time_elapsed_secs\n";
