<?php

function readInput() {
    global $field;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $data = explode(" ", trim($line));
        $field[] = ["springs"=>preg_replace('/\.+/', '.', $data[0]), "counts"=>explode(",", $data[1])];
    }
}

function getValidCount($springs, $counts, &$cache=array()) : int
{
    // check if it's still possible
    if (strlen($springs) < array_sum($counts)+count($counts)-1) return 0;

    // check if we already have calculated this in our cache
    $key = $springs.implode(",", $counts);
    if (isset($cache[$key])) return $cache[$key];

    $total = 0;

    if (empty($counts))
        return str_contains($springs,"#") ? 0 : 1;

    if (empty($springs))
        return 0;

    if (str_contains(".?", $springs[0])) {
        $total += getValidCount(substr($springs, 1), $counts, $cache);
        // update the cache
        $cache[$key] = $total;
    }

    if (str_contains("#?", $springs[0]) &&   // we have a defective spring
        !str_contains(substr($springs,0,$counts[0]), ".") &&  // we don't have any good springs in the range
        (strlen($springs) == $counts[0] || $springs[$counts[0]] != "#")) { // we are at the end or the character after the range is not a defective spring
        $total+=getValidCount(substr($springs,$counts[0]+1), array_slice($counts, 1), $cache);
        // update the cache
        $cache[$key] = $total;
    }

    return $total;
}

function part1() {
    global $field;
    $valid = 0;
    foreach ($field as $row) {
        $valid+=getValidCount($row["springs"], $row["counts"]);
    }
    print "Part 1: The total valid spring configurations is $valid\n";
}

function part2() {
    global $field;
    $valid = 0;
    foreach ($field as $row) {
        $springs = $row["springs"];
        $counts = $row["counts"];
        foreach(range(0, 3) as $i) {
            $springs .= "?".$row["springs"];
            $counts = array_merge($counts, $row["counts"]);
        }
        $valid+=getValidCount($springs, $counts);
    }
    print "Part 2: The total valid spring configurations is $valid\n";
}

readInput();
part1();
part2();
