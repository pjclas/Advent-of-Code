<?php

$enginePlan = array();

function readInput() {
    global $enginePlan;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $enginePlan[] = str_split(trim($line));
    }
}

function findPartsAndGears($enginePlan) {
    $adjacent = [[-1,-1],[-1,0],[-1,1],[0,1],[1,1],[1,0],[1,-1],[0,-1]];
    $num = "";
    $pn = false;
    $gears = array();
    $gearCoords = "";
    $parts = array();
    foreach ($enginePlan as $row=>$data) {
        foreach ($data as $col=>$val) {
            if (is_numeric($val)) {
                // check if there are any symbols next to us
                if (!$pn) {
                    foreach ($adjacent as $adj) {
                        if (isset($enginePlan[$row+$adj[0]][$col+$adj[1]]) &&
                            !is_numeric($enginePlan[$row+$adj[0]][$col+$adj[1]]) && 
                            $enginePlan[$row+$adj[0]][$col+$adj[1]] != ".") {
                            // found a part number
                            $pn = true;

                            // check if this is a gear
                            if ($enginePlan[$row+$adj[0]][$col+$adj[1]] == "*") {
                                $gearCoords = $row+$adj[0].",".$col+$adj[1];
                            }
                            break;
                        }
                    }
                }
                $num.=$data[$col];
            } else {
                if ($num != "") {
                    if ($pn) {
                        $parts[]=$num;
                        if ($gearCoords != "") {
                            $gears[$gearCoords][] = $num;
                        }
                    }
                    $num = "";
                    $pn = false;
                    $gearCoords = "";
                }
            }
        }
    }
    return [$parts, $gears];
}

function part1() {
    global $enginePlan;
    print "Part 1: The sum of the part numbers is ".array_sum(findPartsAndGears($enginePlan)[0])."\n";
}

function part2() {
    global $enginePlan;
    $total = 0;
    $gears = findPartsAndGears($enginePlan)[1];
    foreach ($gears as $gear) {
        if (count($gear) == 2) {
            $total+=$gear[0]*$gear[1];
        }
    }

    print "Part 2: The sum of the gear ratios is $total\n";
}

readInput();
part1();
part2();

?>
