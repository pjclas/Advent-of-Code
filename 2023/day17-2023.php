<?php

class MyHeap extends SplMinHeap
{
    public function compare($item1, $item2):int {
        return ($item1[0]<$item2[0]?1:-1);
    }
}

$dirs = [[-1,0],[0,1],[1,0],[0,-1]];

function readInput()
{
    global $map, $endNode;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $map[] = str_split(trim($line));
    }
    $endNode = [count($map)-1, count($map[0])-1];
}

function findPath($map, $start, $end, $minStepsBeforeTurn=0, $maxStepsForward=3) {
    global $dirs;

    $cache = array();
    $nodes = new MyHeap();
    $nodes->insert([0, $start, 1, 0]);
    while (!$nodes->isEmpty()) {
        list($heat, $pos, $dir, $count) = $nodes->extract();

        if ($pos == $end) {
            return $heat;
        } else {
            // check if we've been here before or if we are back to the start
            $key = $dir.":".$count.":".$pos[0].",".$pos[1];
            if (array_key_exists($key, $cache)) {
                continue;
            }
            // add the node to our cache
            $cache[$key] = 1;

            foreach (range(-1,1) as $d) {
                if ($d == 0) {
                    $nextDir = $dir;
                    // we are going straight
                    if ($count<$maxStepsForward) {
                        $nextCount = $count+1;
                    } else continue;
                } else {
                    if ($count < $minStepsBeforeTurn && $count != 0) continue;
                    // we are turning, figure out the new direction
                    $nextCount = 1;
                    $nextDir=($dir+$d+4)%4;
                }
                $nextPos = [$pos[0]+$dirs[$nextDir][0], $pos[1]+$dirs[$nextDir][1]];
                if ($nextPos == $start) continue;

                // check if we are out of the map
                if (isset($map[$nextPos[0]][$nextPos[1]])) {
                    // check if we can stop at or turn before the end
                    if ($nextCount < $minStepsBeforeTurn) {
                        $moves = $minStepsBeforeTurn-$nextCount;
                        if (($nextDir == 0 && $moves > $nextPos[0]) ||
                            ($nextDir == 1 && $moves > count($map[0])-$nextPos[1]-1) ||
                            ($nextDir == 2 && $moves > count($map)-$nextPos[0]-1) ||
                            ($nextDir == 3 && $moves > $nextPos[1])) {
                            continue;
                        }
                    }
                    $nextHeat=$heat+$map[$nextPos[0]][$nextPos[1]];
                    $nodes->insert([$nextHeat, $nextPos, $nextDir, $nextCount]);
                }
            }
        }
    }
    return 0;
}

function part1() {
    global $map;
    $heatLoss = findPath($map, [0,0], [count($map)-1, count($map[0])-1]);
    print "Part 1: The least heat loss is $heatLoss\n";
}

function part2() {
    global $map;
    $heatLoss = findPath($map, [0,0], [count($map)-1, count($map[0])-1], 4, 10);
    print "Part 2: The least heat loss is $heatLoss\n";
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
