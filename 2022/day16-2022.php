<?php

define("INT_MAX", 2147483647);
define("INT_MIN", -2147483648);

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);

$f = array();
$moves = array();

// example input line
// Valve OJ has flow rate=13; tunnels lead to valves AZ, FP, MY, OL, ET
function readInput() {
    global $f;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $parts = explode(" ", $line);
        $v = $parts[1];
        $f[$v]["flow"] = intval(preg_replace('!\D!', '', $parts[4]));
        $f[$v]["paths"] = explode(", ", preg_replace('!.*valves? !', '', trim($line)));
    }

    // calculate all the shortest paths first
    foreach (array_keys($f) as $v1) {
        if ($f[$v1]["flow"] > 0 || $v1="AA") {
            foreach (array_keys($f) as $v2) {
                if ($f[$v2]["flow"] > 0 && $v1 != $v2) {
                    $f[$v1]["dist"][$v2] = shortestPath($v1, $v2);
                }
            }
        } else $f[$v1]["dist"] = array();
    }
}

function shortestPath($s, $e, $vis = array(), $moves=0) {
    global $f;

    $m = INT_MAX;

    $vis[] = $s;
    if ($s == $e) {
        return $moves;
    }

    foreach($f[$s]["paths"] as $p) {
        if (!in_array($p, $vis)) {
            $m = min($m, shortestPath($p, $e, $vis, $moves+1));
        }
    }

    return $m;
}

function maxFlow2($v, $t, $vis=array(), $total=0) {
    global $f;
    $max = 0;

    // open valve or current node
    if (is_array($v)) {
        foreach($v as $vv=>$d) {
            // check if we reached our valve
            if ($d == $t) {
                $total += $t*$f[$vv]["flow"];  // flow * time left
                unset($v[$vv]);

                // update current locations
                if (!isset($cur1))
                    $cur1 = $vv;
                else
                    $cur2 = $vv;
            }

            // add node to visited
            $vis[$vv] = $d;
        }

        if ($t <= 0) {
            return $total; 
        }
    } else {
        $cur1 = $cur2 = $v;
    }

    // at least one person opened a valve and is ready for another one
    // so let's loop through and try them all
    foreach($f[$cur1]["dist"] as $v1=>$d1) {
        $next = array();
        if (!in_array($v1, array_keys($vis)) && $t>$d1) {
            // see if the other person is also ready for another valve
            if ((!is_array($v) || count($v) == 0) && isset($cur2)) {
                foreach($f[$cur2]["dist"] as $v2=>$d2) {
                    if (!in_array($v2, array_keys($vis)) && $v1!=$v2 && $t>$d2) {
                        $newT = min($d1+1, $d2+1);
                        $flow = maxFlow2([$v1=>$t-($d1+1), $v2=>$t-($d2+1)], $t-$newT, $vis, $total);
                        if ($max < $flow) {
                            $max = $flow;
                        }
                    }
                }
            } else {
                $newT = min($d1+1, $t-current($v));  // compare against time left for other person
                // add new valve target
                $flow = maxFlow2(array_merge([$v1=>$t-($d1+1)], $v), $t-$newT, $vis, $total);
                if ($max < $flow) {
                    $max = $flow;
                }
            }
        }
    }

    // check to see if we need to let the other person finish
    if (is_array($v) && count($v) > 0) {
        // we know we have time to do this so just set the time to the tick time
        // in the last valve
        $flow = maxFlow2($v, current($v), $vis, $total);
        if ($max < $flow) {
            $max = $flow;
        }
    }
    return max($max, $total);
}

function maxFlow($v, $t, $vis=array(), $total=0) {
    global $f;
    $max = 0;

    // open valve or current node
    if (count($vis) > 0) {
        $t--;  // subtract one min to open valve
        $total += $t*$f[$v]["flow"];  // flow * time left

        if ($t <= 0) {
            return $total; 
        }
    }
    // add node to visited
    $vis[] = $v;

    // try paths to all other valves we haven't visited
    foreach($f[$v]["dist"] as $v1=>$d) {
        if (!in_array($v1, $vis) && $t>$d) {
            $flow = maxFlow($v1, $t-$d, $vis, $total);
            if ($max < $flow) {
                $max = $flow;
            }
        }
    }

    return max($max, $total);
}

function part1() {
    global $f;

    $total = maxFlow("AA", 30);
    echo "Part 1: Max flow = $total\n";
}

// part 2 takes 10-15 minutes to run, could certainly be improved...
function part2() {
    global $f;

    $total = maxFlow2("AA", 26);
    echo "Part 2: Max flow = $total\n";
}

readInput();
part1();
part2();

?>
