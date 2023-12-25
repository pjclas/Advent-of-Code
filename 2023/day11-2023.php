<?php

function readInput() {
    global $galaxies, $expandedRows, $expandedCols;
    $lines = file('input.txt');
    foreach ($lines as $row=>$line) {
        // check for space expansion in expandedRows
        if (!str_contains($line, '#')) $expandedRows[] = $row;
        $spaces[] = str_split(trim($line));
    }
    // now check for space expansion in columns
    for ($col=0; $col<count($spaces[0]); $col++) {
        foreach ($spaces as $data) {
            if ($data[$col] == '#') continue 2;  // column doesn't need expansion
        }
        $expandedCols[] = $col;
    }

    foreach ($spaces as $row=>$data) {
        foreach ($data as $col=>$space) {
            if ($space == '#') $galaxies[] = [$row, $col];
        }
    }
}

function getDistance($expansionMultiple): int
{
    global $galaxies, $expandedRows, $expandedCols;

    $total = 0;
    foreach ($galaxies as $gal1 => $pos) {
        for ($gal2 = $gal1 + 1; $gal2 < count($galaxies); $gal2++) {
            $total += abs($pos[0] - $galaxies[$gal2][0]) + abs($pos[1] - $galaxies[$gal2][1]);
            // now add the expansions
            foreach ($expandedRows as $row) {
                if ($pos[0] < $row && $galaxies[$gal2][0] > $row) $total += $expansionMultiple-1;
            }
            foreach ($expandedCols as $col) {
                if (min($pos[1], $galaxies[$gal2][1]) < $col && max($pos[1], $galaxies[$gal2][1]) > $col) $total += $expansionMultiple-1;
            }
        }
    }
    return $total;
}

function part1() {
    print "Part 1: The total distance between galaxies is ".getDistance(2)."\n";
}

function part2() {
    print "Part 1: The total distance between galaxies is ".getDistance(1000000)."\n";
}

readInput();
part1();
part2();
