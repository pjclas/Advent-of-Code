<?php

$data = "10111100110001111";

function findChecksum($data, $discSize)
{
    while (strlen($data) < $discSize) {
        $data .= "0".str_replace(["0","1","2"],["2","0","1"],strrev($data));
    }
    $data = substr($data,0,$discSize);
    do {
        $checksum = "";
        for ($i = 0; $i < strlen($data); $i += 2) {
            $checksum .= ($data[$i] == $data[$i + 1]) ? "1" : "0";
        }
        $data = $checksum;
    } while (strlen($checksum)%2 == 0);

    return $checksum;
}
function part1(): void
{
    global $data;
    echo "Part 1: Checmsum = ".findChecksum($data,272)."\n";
}

function part2(): void
{
    global $data;
    echo "Part 2: Checmsum = ".findChecksum($data,35651584)."\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
