<?php

$screen = array();
$ops = array();
function readInput(): void
{
    global $screen, $ops;
    for ($i=0; $i<6; $i++)
        $screen[$i] = array_fill(0,50, ".");

    $lines = file('input.txt');
    foreach ($lines as $line) {
        $ops[] = explode(" ", trim($line));
    }
}


function part1(): void
{
    global $screen, $ops;
    foreach ($ops as $op) {
        if ($op[0] == "rect") {
            list($col, $row) = explode("x", $op[1]);
            for ($r=0; $r<$row; $r++) {
                for ($c=0; $c<$col; $c++) {
                    $screen[$r][$c]="#";
                }
            }
        } else if ($op[1] == "row") {
            $row = explode("=", $op[2])[1];
            $newRow = array();
            foreach ($screen[$row] as $c=>$pix) {
                $newRow[($c+$op[4])%count($screen[$row])] = $pix;
            }
            ksort($newRow);
            $screen[$row] = $newRow;
        } else {
            $col = explode("=", $op[2])[1];
            $newScreen = $screen;
            foreach ($screen as $r=>$row) {
                $newScreen[($r+$op[4])%count($screen)][$col] = $row[$col];
            }
            $screen = $newScreen;
        }
    }
    $lights = 0;
    foreach ($screen as $r) {
        $lights+=array_count_values($r)["#"];
    }

    echo "Part 1: Number of lights on = $lights\n";
}

function part2(): void
{
    global $screen;
    echo "Part 2: \n";
    foreach ($screen as $row) {
        foreach ($row as $pix) {
            echo "$pix";
        }
        echo "\n";
    }
}

readInput();
$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
