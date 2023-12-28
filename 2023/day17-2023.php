<?php
const INT_MAX = 2147483647;

$dirs = [[-1,0],[0,1],[1,0],[0,-1]];
$leastHeatLoss = INT_MAX;
function readInput()
{
    global $map, $endNode;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $map[] = str_split(trim($line));
    }
    $endNode = [count($map)-1, count($map[0])-1];
}

function findPath1($node, &$cache = array(), $trace="") {
    global $map, $dirs, $leastHeatLoss, $endNode, $data;
    $key = $node["dir"].$node["dirCount"].implode(',',$node["pos"]);

    if ($node["pos"] == $endNode) {
        if ($node["loss"] < $leastHeatLoss) $leastHeatLoss = $node["loss"];
        print "End node heat loss = $leastHeatLoss\n";
        return true;
    }

    // check if we've been here before or if we are out of the map
    if (array_key_exists($key, $cache)) {
        if ($cache[$key]["loss"] > $node["loss"]) {
          //  print "decreasing loss from ".$cache[$key]["loss"]." to ".$node["loss"]."\n";
            $cache[$key]["loss"] = $node["loss"];
        }
        else {
            return false;
        }
    }
    if ($node["loss"] >= $leastHeatLoss) return false;

   // $data.="$trace$key - loss=".$node["loss"]."\n";
    $cache[$key] = $node;
    foreach (range(-1,1) as $dir) {
        $newNode = $node;
        if ($dir == 0) {
            // we are going straight
            if ($newNode["dirCount"]>0) {
                $newNode["dirCount"]--;
            } else continue;
        } else {
            // we are turning, figure out the new direction
            $newNode["dirCount"] = 2;
            $newNode["dir"]+=$dir+4;
            $newNode["dir"]%=4;
        }
        $newNode["pos"] = [$newNode["pos"][0]+$dirs[$newNode["dir"]][0],$newNode["pos"][1]+$dirs[$newNode["dir"]][1]];
        if (isset($map[$newNode["pos"][0]][$newNode["pos"][1]])) {
            $newNode["loss"]+=$map[$newNode["pos"][0]][$newNode["pos"][1]];
            findPath1($newNode, $cache, " >" . $trace);
        }
    }

    return false;
}

function findPath2($node, &$cache = array(), $trace="") {
    global $map, $dirs, $leastHeatLoss, $endNode, $data;
    $key = $node["dir"].$node["dirCount"].implode(',',$node["pos"]);

    // check if we can stop at or turn before the end
    if ($node["dirCount"] < 4) {
        $moves = 4-$node["dirCount"];
        if (($node["dir"] == 0 && $moves > $node["pos"][0]) ||
            ($node["dir"] == 1 && $moves > (count($map[0])-$node["pos"][1])-1) ||
            ($node["dir"] == 2 && $moves > (count($map)-$node["pos"][0])-1) ||
            ($node["dir"] == 3 && $moves > $node["pos"][1])) {
            //  print implode(",",$node["pos"])." can't go dir ".$node["dir"].", required $moves ".$node["dirCount"]."\n";
            return false;
        }
    }

    if ($node["pos"] == $endNode) {
        if ($node["loss"] < $leastHeatLoss) $leastHeatLoss = $node["loss"];
        print "End node heat loss = $leastHeatLoss\n";
        print $node["path"]."\n";
        return true;
    }

    // check if we've been here before or if we are out of the map
    if (array_key_exists($key, $cache)) {
        if ($cache[$key]["loss"] > $node["loss"]) {
            //  print "decreasing loss from ".$cache[$key]["loss"]." to ".$node["loss"]."\n";
            $cache[$key]["loss"] = $node["loss"];
        }
        else {
            return false;
        }
    }
    if ($node["loss"] >= $leastHeatLoss) return false;

    //$data.="$trace$key - loss=".$node["loss"]."\n";
    $cache[$key] = $node;
    foreach (range(-1,1) as $dir) {
        $newNode = $node;
        if ($dir == 0) {
            // we are going straight
            if ($newNode["dirCount"]<10) {
                $newNode["dirCount"]++;
            } else continue;
        } else {
            if ($newNode["dirCount"] < 4 && $newNode["dirCount"] != 0) continue;
            // we are turning, figure out the new direction
            $newNode["dirCount"] = 1;
            $newNode["dir"]+=$dir+4;
            $newNode["dir"]%=4;
        }
        $newNode["pos"] = [$newNode["pos"][0]+$dirs[$newNode["dir"]][0],$newNode["pos"][1]+$dirs[$newNode["dir"]][1]];
        if (isset($map[$newNode["pos"][0]][$newNode["pos"][1]])) {
            $newNode["loss"]+=$map[$newNode["pos"][0]][$newNode["pos"][1]];
            $newNode["path"].=" ".$newNode["pos"][0].",".$newNode["pos"][1];
            findPath2($newNode, $cache, " >" . $trace);
        }
    }

    return false;
}

function findPath3($map, $start, $end) {
    global $dirs;

    $cache = array();
    $nodes = [[0, $start[0], $start[1], 1, 0]];
    while (!empty($nodes)) {
        list($heat, $r, $c, $dir, $count) = array_shift($nodes);

        if ([$r,$c] == $end) {
            return $heat;
        } else {
            // check if we've been here before or if we are back to the start
            $key = $dir.":".$count.":".$r.",".$c;
            if (array_key_exists($key, $cache)) {
                continue;
            }
            // add the node to our cache
            $cache[$key] = 1;

            foreach (range(-1,1) as $d) {
                if ($d == 0) {
                    $nextDir = $dir;
                    // we are going straight
                    if ($count<10) {
                        $nextCount = $count+1;
                    } else continue;
                } else {
                    if ($count < 4 && $count != 0) continue;
                    // we are turning, figure out the new direction
                    $nextCount = 1;
                    $nextDir=($dir+$d+4)%4;
                }
                $nextRow = $r+$dirs[$nextDir][0];
                $nextCol = $c+$dirs[$nextDir][1];
                if ([$nextRow, $nextCol] == $start) continue;

                // check if we are out of the map
                if (isset($map[$nextRow][$nextCol])) {
                    // check if we can stop at or turn before the end
/*                    if ($nextCount < 4) {
                        $moves = 4-$nextCount;
                        if (($nextDir == 0 && $moves > $nextRow) ||
                            ($nextDir == 1 && $moves > count($map[0])-$nextCol-1) ||
                            ($nextDir == 2 && $moves > count($map)-$nextRow-1) ||
                            ($nextDir == 3 && $moves > $nextCol)) {
                            continue;
                        }
                    }*/
                    $nextHeat=$heat+$map[$nextRow][$nextCol];
                    $nodes[] = [$nextHeat, $nextRow, $nextCol, $nextDir, $nextCount];
                    array_multisort(array_column($nodes, 0), SORT_ASC, $nodes);
                }
            }
        }
    }
}
function findPath4($map, $start, $end) {
    global $dirs;

    $height = count($map);
    $width = count($map[0]);
    $cache = array();
    $nodes = [[0, $start[0], $start[1], 0, 0, 0]];
    while ($nodes) {
        list($heat, $r, $c, $dr, $dc, $count) = array_shift($nodes);

        // check if we've been here before or if we are back to the start
        $key = $r . "," . $c . ":" . $dr . "," . $dc . ":" . $count;
        if (isset($cache[$key])) continue;
        if ($r == $height-1 && $c == $width-1) {
            return $heat;
        }
        // add the node to our cache
        $cache[$key] = 1;

        foreach ($dirs as list($next_dr, $next_dc)) {
            $nextR = $r + $next_dr;
            $nextC = $c + $next_dc;
            if ($nextR < 0 || $nextC < 0 || $nextR >=$height || $nextC >= $width) continue;
            if ($next_dr==-$dr && $next_dc==-$dc) continue;
            if ($next_dr==$dr && $next_dc==$dc) {
                if ($count < 10)
                    $nodes[] = [$heat+$map[$nextR][$nextC], $nextR, $nextC, $next_dr, $next_dc, $count+1];
                else continue;
            } elseif ($count>=4 || ($dr==0 && $dc==0)) {
                $nodes[] = [$heat+$map[$nextR][$nextC], $nextR, $nextC, $next_dr, $next_dc, 1];
            }
            array_multisort(array_column($nodes, 0), SORT_ASC, $nodes);
        }
    }
}

function part1() {
    global $leastHeatLoss, $map;
    $node["dir"] = 1;
    $node["dirCount"] = 3;
    $node["pos"] = [0,0];
    $node["loss"] = 0;
    $col=1;
    $leastHeatLoss = 0;
    $end = [count($map)-1, count($map[0])-1];
    foreach ($map as $row=>$data) {
        if ([$row,$col]==$end) break;
        $leastHeatLoss+=$map[$row][$col];

        $leastHeatLoss+=$map[$row+1][$col];
        if ([$row+1,$col]==$end) break;
        $col++;
    }
    findPath1($node);

    print "Part 1: The least heat loss is $leastHeatLoss\n";
}

function part2() {
    global $leastHeatLoss, $map, $data;
/*    $data="";
    $leastHeatLoss = 0;
    $numLoops = intdiv((count($map)-1),4)-1;
    $remain = (count($map)-1)%4+4;
    $row=$col=0;
    foreach (range(0,$numLoops-1) as $c) {
        for ($j = 0; $j < 4; $j++) {
            $col++;
            $leastHeatLoss += $map[$row][$col];
        }
        for ($i=0; $i < 4; $i++) {
            $row++;
            $leastHeatLoss += $map[$row][$col];
        }
    }
    for ($j = 0; $j < $remain; $j++) {
        $col++;
        $leastHeatLoss += $map[$row][$col];
    }
    for ($i=0; $i < $remain; $i++) {
        $row++;
        $leastHeatLoss += $map[$row][$col];
    }*/
    $heatLoss = findPath4($map, [0,0], [count($map)-1, count($map[0])-1]);
    //file_put_contents("debug.txt", $data);

    print "Part 2: The least heat loss is $heatLoss\n";
}
readInput();
//part1();
part2();
