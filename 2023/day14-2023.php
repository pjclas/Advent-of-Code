<?php

function readInput() {
    global $field;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $field[] = str_split(trim($line));
    }
}

function rollRocks($field, $dir) {
    $newField = $field;
    // need to do the bottom rows first if we are tipping south
    $rowRange = ($dir[0]==1)?[count($field)-1,0]:[0,count($field)-1];
    // need to do the right cols first if we are tipping east
    $colRange = ($dir[1]==1)?[count($field[0])-1,0]:[0,count($field[0])-1];
    foreach (range($rowRange[0], $rowRange[1]) as $row) {
        foreach (range($colRange[0], $colRange[1]) as $col) {
            if ($newField[$row][$col] == "O") {
                $swapPos = [$row,$col];
                $newRow = $swapPos[0]+$dir[0];
                $newCol = $swapPos[1]+$dir[1];
                while (isset($newField[$newRow]) && isset($newField[$newRow][$newCol]) &&
                       $newField[$newRow][$newCol] == ".") {
                    $swapPos = [$newRow,$newCol];
                    $newRow = $swapPos[0]+$dir[0];
                    $newCol = $swapPos[1]+$dir[1];
                }
                if ($swapPos[0] != $row || $swapPos[1] != $col) {
                    $newField[$swapPos[0]][$swapPos[1]] = "O";
                    $newField[$row][$col] = ".";
                }
            }
        }
    }
    return $newField;
}
function part1() {
    global $field;
    $total = 0;
    $newField = rollRocks($field, [-1,0]);
    foreach ($newField as $row=>$cols) {
        foreach ($cols as $space) {
            if ($space == "O") $total+=count($field)-$row;
        }
    }
    print "Part 1: The total load is $total\n";
}

function part2()
{
    $dirs = [[-1, 0], [0, -1], [1, 0], [0, 1]];
    global $field;
    $total = 0;
    $newField = $field;
    $patterns = array();
    $done = false;
    $count = 1000000000;
    do {
        foreach ($dirs as $dir) {
            $newField = rollRocks($newField, $dir);
        }
        $key = md5(serialize($newField));
        if (!array_key_exists($key,$patterns)) {
            $patterns[$key] = $newField;
            $count--;
        } else $done = true;
    } while (!$done);

    // we repeated the pattern, calculate our position based on the moves left
    // reduce the array to the portion that is repeated
    $patterns = array_slice($patterns, array_search($key, array_keys($patterns)),null, true);

    $key = array_keys($patterns)[$count % count($patterns) - 1];
    $newField = $patterns[$key];
    foreach ($newField as $row=>$cols) {
        foreach ($cols as $space) {
            if ($space == "O") $total+=count($field)-$row;
        }
    }

    print "Part 2: The total load after a spin cycle is $total\n";
}
readInput();
part1();
part2();
