<?php

$trapRow = "^^.^..^.....^..^..^^...^^.^....^^^.^.^^....^.^^^...^^^^.^^^^.^..^^^^.^^.^.^.^.^.^^...^^..^^^..^.^^^^";

function totalSafeTiles($rows)
{
    global $trapRow;
    $safe = substr_count($trapRow, ".");
    $pRow = str_replace(["^","."],[1,0],$trapRow);
    for ($r=1; $r<$rows; $r++) {
        $row = "0".$pRow."0";
        $pRow = "";
        for ($c=1; $c<=strlen($trapRow); $c++) {
            $a = intval($row[$c-1]);
            $b = intval($row[$c+1]);
            $pRow.= (int)(($a | $b) == 1 && ($a & $b) == 0);
            if ($pRow[$c-1] == '0') $safe++;
        }
    }

    return $safe;
}

function part1(): void
{
    echo "Part 1: Number of safe tiles = ".totalSafeTiles(40)."\n";
}

function part2(): void
{
    echo "Part 2: Number of safe tiles = ".totalSafeTiles(400000)."\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
