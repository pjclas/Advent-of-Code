<?php

$rooms = array();
function readInput(): void
{
    global $rooms;
    $lines = file('input.txt');
    foreach ($lines as $k=>$line) {
        $in = explode("-", trim($line));
        preg_match_all("/(\d+)\[(.*)\]/", array_pop($in), $d);
        $rooms[$k]["name"] = implode("-", $in);
        $rooms[$k]["id"] = $d[1][0];
        $rooms[$k]["checksum"] = $d[2][0];
    }
}

function part1(): void
{
    global $rooms;
    $sum = 0;
    foreach ($rooms as $room) {
        $d = array_count_values(str_split(str_replace("-", "", $room["name"])));
        ksort($d);
        arsort($d);
        $d = array_keys($d);
        if ($d[0].$d[1].$d[2].$d[3].$d[4] == $room["checksum"]) {
            $sum+=$room["id"];
        }
    }

    echo "Part 1: Sum of sector ids for real rooms = $sum\n";
}

function part2(): void
{
    global $rooms;
    $id = 0;
    $orda = ord("a");
    foreach ($rooms as $room) {
        $letters = str_split($room["name"]);
        foreach ($letters as $k=>$letter) {
            // 97-122
            if ($letter != "-") {
                $letters[$k] = chr((($room["id"] + ord($letter) - $orda) % 26) + $orda);
            } else {
                $letters[$k] = " ";
            }
        }
//        echo implode("", $letters)."\n";
        if (str_contains(implode("", $letters), "northpole")) {
            $id = $room["id"];
            break;
        }
    }

    echo "Part 2: Sector id for North Pole objects = $id\n";
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
