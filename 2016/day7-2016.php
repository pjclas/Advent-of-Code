<?php

$ips = array();
function readInput(): void
{
    global $ips;
    $lines = file('../input.txt');
    foreach ($lines as $k=>$line) {
        preg_match_all("/\[(.*)]/U", $line, $hyper);
        $ips[$k]["hyper"] = $hyper[1];
        $ips[$k]["ip"] = preg_replace('/(\[.*])/U', '-', trim($line));
    }
}

function isAbba($s) {
    $ip = str_split($s);
    for ($i=0; $i<count($ip)-3; $i++) {
        if ($ip[$i] == $ip[$i+3] && $ip[$i+1] == $ip[$i+2] && $ip[$i] != $ip[$i+1] && $ip[$i] != "-" && $ip[$i+1] != "-") return true;
    }
    return false;
}

function supportsSsl($aba, $bab) {
    $ip = str_split($aba);
    for ($i=0; $i<count($ip)-2; $i++) {
        if ($ip[$i] == $ip[$i+2] && $ip[$i] != $ip[$i+1] && $ip[$i] != "-" && $ip[$i+1] != "-") {
            if (str_contains($bab, $ip[$i+1].$ip[$i].$ip[$i+1])) return true;
        }
    }
    return false;
}
function part1(): void
{
    global $ips;
    $tls = 0;
    foreach ($ips as $ip) {
        if (isAbba($ip["ip"])) {
            foreach ($ip["hyper"] as $h) {
                if (isAbba($h)) continue 2;
            }
            $tls++;
        }
    }

    echo "Part 1: Number of ip addresses that support TLS = $tls\n";
}

function part2(): void
{
    global $ips;
    $ssl = 0;
    foreach ($ips as $ip) {
        foreach ($ip["hyper"] as $h) {
            if (supportsSsl($ip["ip"], $h)) {
                $ssl++;
                continue 2;
            }
        }
    }

    echo "Part 2: Number of ip addresses that support SSL = $ssl\n";
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
