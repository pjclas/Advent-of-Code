<?php

function readInput() {
    global $patterns;
    $lines = file('input.txt');
    $count=0;
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) $count++;
        else $patterns[$count][] = $line;
    }
}

function findSymmetry($pattern, $blacklist=array()) {
    // check for symmetry in rows first
    foreach (range(0,count($pattern)-2) as $row) {
        // skip anything that is blacklisted
        if (isset($blacklist["row"]) && $row==$blacklist["row"]) continue;
        $row1 = $row;
        $row2 = $row+1;
        while ($pattern[$row1] == $pattern[$row2]) {
            if ($row1==0 || $row2==count($pattern)-1) {
                return array("row"=>$row);
            }
            $row1--;
            $row2++;
        }
    }
    // now check for symmetry in columns
    foreach (range(0,strlen($pattern[0])-2) as $col) {
        // skip anything that is blacklisted
        if (isset($blacklist["col"]) && $col==$blacklist["col"]) continue;

        $col1 = $col;
        $col2 = $col+1;
        do {
            foreach ($pattern as $p) {
                if ($p[$col1] != $p[$col2]) continue 3;
            }
            $col1--;
            $col2++;
        } while ($col1>=0 && $col2<=strlen($pattern[0])-1);
        return array("col"=>$col);
    }

    return array();
}
function part1() {
    global $patterns;
    $total=0;
    foreach ($patterns as $pattern) {
        $ret=findSymmetry($pattern);
        if (isset($ret["row"])) $total+=100*($ret["row"]+1);
        elseif (isset($ret["col"])) $total+=$ret["col"]+1;
    }
    print "Part 1: The total of summarized patterns is $total\n";
}

function part2() {
    global $patterns;
    $total=0;
    foreach ($patterns as $pattern) {
        $ret=findSymmetry($pattern);
        foreach ($pattern as $row=>$p) {
            foreach (range(0,strlen($p)-1) as $col) {
                $newPattern = $pattern;
                $newPattern[$row][$col] = $newPattern[$row][$col] == "#" ? "." : "#";
                $newRet = findSymmetry($newPattern, $ret);
                if (!empty($newRet)) break 2;
            }
        }
        if (isset($newRet["row"])) $total+=100*($newRet["row"]+1);
        elseif (isset($newRet["col"])) $total+=$newRet["col"]+1;
    }
    print "Part 2: The total of summarized patterns without smudges is $total\n";
}

readInput();
part1();
part2();
