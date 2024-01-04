<?php

$blocks = array();
function readInput(): void
{
    global $blocks;
    $lines = file('input.txt');
    foreach ($lines as $key=>$line) {
        list($end1,$end2) = explode("~", trim($line));
        list($x1,$y1,$z1) = explode(",", $end1);
        list($x2,$y2,$z2) = explode(",", $end2);
        // list block points from bottom to top
        if ($z1<$z2) {
            $p1 = ["x"=>$x1,"y"=>$y1,"z"=>$z1];
            $p2 = ["x"=>$x2,"y"=>$y2,"z"=>$z2];
        } else {
            $p1 = ["x"=>$x2,"y"=>$y2,"z"=>$z2];
            $p2 = ["x"=>$x1,"y"=>$y1,"z"=>$z1];
        }
        $blocks[$p1["z"]][$key] = [$p1, $p2];
    }
    // need to start from ground up
    ksort($blocks);
}

function overlap($rect1, $rect2) : bool {
    $l1 = [min($rect1[0]["x"], $rect1[1]["x"]), max($rect1[0]["y"], $rect1[1]["y"])];
    $r1 = [max($rect1[0]["x"], $rect1[1]["x"]), min($rect1[0]["y"], $rect1[1]["y"])];
    $l2 = [min($rect2[0]["x"], $rect2[1]["x"]), max($rect2[0]["y"], $rect2[1]["y"])];
    $r2 = [max($rect2[0]["x"], $rect2[1]["x"]), min($rect2[0]["y"], $rect2[1]["y"])];

    if ($r1[0] < $l2[0] || $r2[0] < $l1[0] ||
        $l1[1] < $r2[1] || $l2[1] < $r1[1])
        return false;

    return true;
}

function dropBlocks($blocks): void
{
    global $supporting, $supportedBy;
    $staticBlocks = array();
    $supporting = $supportedBy = array();
    foreach ($blocks as $bottoms) {
        foreach ($bottoms as $key=>$rect) {
            list($p1, $p2) = $rect;
            $supporting[$key] = [];
            $supportedBy[$key] = [];
            if ($p1["z"] == 1)
                $staticBlocks[$p2["z"]][$key] = [$p1, $p2];  // we are on the ground
            else {
                // find the highest block that we overlap
                $found = false;
                foreach ($staticBlocks as $tops) {
                    foreach ($tops as $key2=>$sBlock) {
                        $sp2 = $sBlock[1];
                        if ($sp2["z"] < $p1["z"]) {
                            if (overlap($rect, $sBlock)) {
                                if (!$found) {
                                    $found = true;
                                    $diff = $p1["z"] - $sp2["z"] - 1;
                                    $p1["z"] -= $diff;
                                    $p2["z"] -= $diff;
                                    $staticBlocks[$p2["z"]][$key] = [$p1, $p2];
                                }
                                // add support information for all blocks supporting at this height
                                if ($sp2["z"] + 1 == $p1["z"]) {
                                    // add support information
                                    $supporting[$key2][] = $key;
                                    $supportedBy[$key][] = $key2;
                                } else continue 2; // no more blocks at the level immediately below us
                            }
                        } else continue 2; // these blocks are higher than our bottom point
                    }
                }
                if (!$found) {
                    // block reaches the ground
                    $diff = $p1["z"] - 1;
                    $p1["z"] -= $diff;
                    $p2["z"] -= $diff;
                    $staticBlocks[$p2["z"]][$key] = [$p1, $p2];
                }
                krsort($staticBlocks);
            }
        }
    }
}

function getCollapseCount($block) {
    global $supporting, $supportedBy;
    $collapsed[$block] = 1;
    $q = [$block];
    while ($q) {
        $block = array_shift($q);
        foreach ($supporting[$block] as $b) {
            if (!array_key_exists($b, $collapsed)) {
                $supports = count($supportedBy[$b]);
                foreach ($supportedBy[$b] as $s) {
                    if (array_key_exists($s, $collapsed)) $supports--;
                }
                if ($supports == 0) {  // we are the only support
                    $collapsed[$b] = 1;
                    $q[] = $b;
                }
            }
        }
    }
    return count($collapsed)-1;
}

function part1(): void
{
    global $supporting, $supportedBy;
    $count=0;
    foreach ($supporting as $blks) {
        if (empty($blks)) $count++;
        else {
            // check if the blocks we are supporting are supported by other blocks as well
            foreach ($blks as $b) {
                if (count($supportedBy[$b]) == 1) continue 2;
            }
            // if we got here then all blocks we are supporting have additional supports
            $count++;
        }
    }
    print "Part 1: The number of blocks that can be safely removed is $count \n";
}

function part2(): void
{
    global $supporting;
    $count=0;
    foreach(array_keys($supporting) as $b) {
        $count+= getCollapseCount($b);
    }
    print "Part 2: The number of collapsed blocks is $count \n";
}

readInput();
dropBlocks($blocks);
part1();
part2();
