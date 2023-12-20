<?php
define("INT_MAX", 2147483647);

$seeds = array();
$maps = array();

function readInput() {
    global $seeds, $maps;
    $lines = file('input.txt');
    preg_match_all('/\d+/', array_shift($lines), $s);
    $seeds = $s[0];
    $map = array();
    foreach ($lines as $line) {
        if (!empty(trim($line))) {
            if (str_contains($line, "map:")) {
                if (!empty($map)) {
                    // this is a new mapping
                    $maps[] = $map;
                    $map = array();
                }
            } else {
                $values = explode(" ", trim($line));
                $map[$values[1]] = array("srcStart"=>$values[1], "srcEnd"=>(int)$values[1]+(int)$values[2]-1, "destStart"=>$values[0], "destEnd"=>(int)$values[0]+(int)$values[2]-1);
                asort($map);
            }
        }
    }
    // add last mapping to array
    $maps[] = $map;
}

function findDestination($maps, $val) {
    $dest = $val;

    foreach ($maps as $map) {
        if ($map["srcStart"] <= $val && $map["srcEnd"] >= $val) {
            // found our mapping
            $dest = $map["destStart"] + ($val-$map["srcStart"]);
            break;
        }
    }

    return $dest;
}

function part1() {
    global $seeds, $maps;
    $location = INT_MAX;
    foreach ($seeds as $seed) {
        $val = $seed;
        foreach ($maps as $map) {
            $val = findDestination($map, $val);
        }
        if ($val < $location) $location = $val;
    }

    print "Part 1: The lowest location number for seeds is $location\n";
}

function part2() {
    global $seeds, $maps;
    $ranges = array();
    for ($start=0; $start<count($seeds); $start+=2) {
        $ranges[] = [$seeds[$start], $seeds[$start]+$seeds[$start+1]];
    }
    // break each range up into new ranges based on next mapping
    foreach ($maps as $map) {
        $newRanges = array();
        foreach ($ranges as $range) {
            foreach ($map as $data) {
                // check if this range overlaps
                $max = max($data["srcStart"], $range[0]);
                $min = min($data["srcEnd"], $range[1]);
                if ($max <= $min) {
                    // this range overlaps, let's break it up if not all intersects
                    if ($range[0] < $data["srcStart"]) {
                        // we have some values before the intersection, they remain unchanged
                        $newRanges[$range[0]] = [$range[0], $data["srcStart"] - 1];
                    }
                    // map the intersecting ranges
                    $start = $max+($data["destStart"]-$data["srcStart"]);
                    $newRanges[$start] = [$start, $min+($data["destStart"]-$data["srcStart"])];
                    if ($range[1] > $data["srcEnd"]) {
                        // we have some values after the intersection, replace the range with what's left
                        $range = array($data["srcEnd"] + 1, $range[1]);
                    } else {
                        // we are done with this range
                        continue 2;
                    }
                }
            }
            // if we got here then our range does not intersect any of the mappings at this level
            $newRanges[$range[0]] = $range;
        }
        $ranges = $newRanges;
        asort($ranges);
    }

    print "Part 2: The lowest location number for seeds is ".array_shift($ranges)[0]."\n";
}

readInput();
part1();
part2();
