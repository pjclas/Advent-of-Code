<?php

function readInput(): void
{
    global $components;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        list($key, $comps) = explode(": ", trim($line));
        $components[$key] = explode(" ", $comps);
    }
}

function keyname($v1,$v2) {
    $vs = array_merge(explode("-", $v1),explode("-", $v2));
    sort($vs);
    return implode("-", $vs);
}

// refer to karger algorithm for finding min-cut for a graph
// could also consider stoer-wagner algorithm instead
function contract($G, $t) : array {
    list($V,$E,$map) = $G;
    while (count($V) > $t) {
        list($v1,$v2) = explode("-", array_rand($E));
        // update the vertices from our mapping if necessary
        [$v1,$v2] = [$map[$v1]??$v1, $map[$v2]??$v2];
        // remove edges between vertices we are combining
        foreach (explode("-", $v1) as $e1)
            foreach (explode("-", $v2) as $e2) {
                $key = keyname($e1, $e2);
                if (array_key_exists($key, $E))
                    unset($E[$key]);
            }
        $n = keyname($v1,$v2);
        // add mappings for new node
        foreach (explode("-", $n) as $old)
            $map[$old] = $n;
        $V[$n] = $V[$v1]+$V[$v2];
        unset($V[$v1], $V[$v2]);
    }
    return [$V,$E,$map];
}

// refer to karger-stein algorithm for finding min-cut for a graph
function fastmincut($G) : array {
    $count = count($G[0]);
    if ($count <= 6)
        return contract($G, 2);
    $t = 1+intval($count/sqrt(2));
    $G1 = fastmincut(contract($G, $t));
    $G2 = fastmincut(contract($G, $t));
    return (count($G1[1]) <= count($G2[1]))?$G1:$G2;
}

function part1(): void
{
    global $components;
    $V=$E = array();
    foreach ($components as $c1=>$comps) {
        $V[$c1]=1;
        foreach ($comps as $c2) {
            $V[$c2]=1;
            $E[keyname($c1, $c2)] = 1;
        }
    }
    do {
        // uncomment this if we don't already know the mincut we are looking for and
        // need to traverse through all possible nodes.
        // [$V2, $E2,$map] = fastmincut([$V, $E, []]);
        [$V2, $E2] = contract([$V, $E, []], 2);
        print json_encode(array_keys($E2))."\n";
    } while (count($E2)!=3);

    print "The product of the two groups is ".array_product($V2)."\n";
}

readInput();
part1();
