<?php

$id = "cxdnnyjw";

function part1(): void
{
    global $id;
    $count = 0;
    $i=0;
    $pw = "";
    while ($count<8) {
        $hash = md5($id.$i);
        if (str_starts_with($hash, "00000")) {
            $pw.=substr($hash, 5, 1);
            $count++;
        }
        $i++;
    }

    echo "Part 1: Password = $pw\n";
}

function part2(): void
{
    global $id;
    $count = 0;
    $i=0;
    $pw = array();
    while ($count<8) {
        $hash = md5($id.$i);
        if (str_starts_with($hash, "00000")) {
            $pos = substr($hash, 5, 1);
            if (!array_key_exists($pos, $pw) && $pos < 8) {
                $pw[substr($hash, 5, 1)] = substr($hash, 6, 1);
                $count++;
            }
        }
        $i++;
    }
    ksort($pw);

    echo "Part 2: Password = ".implode("",$pw)."\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
