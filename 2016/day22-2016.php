<?php

$nodes = array();
function readInput(): void
{
    global $nodes;
    $lines = file('input.txt');
    foreach ($lines as $k=>$line) {
        $data = preg_split("! +!", trim($line));
        $coords = explode("-", str_replace("/dev/grid/node-", "",$data[0]));
        $nodes[intval(substr($coords[1], 1))][intval(substr($coords[0], 1))] = ["id"=>$k, "size"=>intval($data[1]), "used"=>intval($data[2]), "avail"=>intval($data[3])];
    }
}

function findPath($nodes, $targetId)
{
    $walls[] = array_fill(0,count($nodes[0])+2, 1);
    foreach ($nodes as $r=>$cols) {
        $walls[$r+1][0] = $walls[$r+1][count($nodes[0]) + 1] = 1;
        foreach ($cols as $c => $node) {
            if ($node["used"] == 0) $start = [$r + 1, $c + 1];
            else if ($node["id"] == $targetId) $target = [$r + 1, $c + 1];
            else if ($node["used"] > 100) $walls[$r + 1][$c + 1] = 1;
        }
    }
    $walls[] = array_fill(0,count($nodes[0])+2, 1);

    $dirs = [[1,0],[-1,0],[0,1],[0,-1]];
    $q = [["pos"=>$start, "target"=>$target, "moves"=>0]];
    $vis = [implode(",",$start).",".implode(",",$target)=> 1];
    while (count($q)>0) {
        $v = array_shift($q);
        if ($v["target"] == [1,1]) {
            echo "Found path in ".$v["moves"]." moves.\n";
            break;
        }
        foreach ($dirs as $dir) {
            $newLoc = [$v["pos"][0]+$dir[0],$v["pos"][1]+$dir[1]];
            // check if we can move data to this node
            if (!isset($walls[$newLoc[0]][$newLoc[1]])) {
                if ($newLoc == $v["target"])
                    $newTarget = $v["pos"];
                else
                    $newTarget = $v["target"];
                $key = implode(",",$newLoc).",".implode(",",$newTarget);
               // echo "Checking key $key\n";
                // check if we already tried this position
                if (!array_key_exists($key, $vis)) {
                    $vis[$key] = 1;
                    $q[] = ["pos"=>$newLoc, "target"=>$newTarget, "moves"=>$v["moves"]+1];
                }
            }
        }
    }

    return $v["moves"];
}

function part1(): void
{
    global $nodes;
    $pairs = 0;
    $n = array();
    // flatten the data for simplicity
    foreach ($nodes as $cols)
        foreach ($cols as $node)
            $n[] = $node;
    for ($k1=0; $k1<count($n)-1; $k1++) {
        for ($k2=$k1+1; $k2<count($n); $k2++) {
            if ($n[$k1]["used"]<=$n[$k2]["avail"]) $pairs++;
        }
    }

    echo "Part 1: Number of viable pairs = $pairs\n";
}

function part2(): void
{
    global $nodes;
    echo "Part 2: Number of steps = ".findPath($nodes, $nodes[0][count($nodes[0])-1]["id"])."\n";
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
