<?php

const INT_MAX = 9223372036854775807;
const INT_MIN = -9223372036854775808;

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);
$reindeer = array();
function readInput(): void
{
    global $reindeer;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $data = explode(" ", trim($line));
        $reindeer[$data[0]] = ["speed"=>$data[3], "fly"=>$data[6], "rest"=>$data[13], "points"=>0];
    }
}

function calculateDistance($time, $speed, $flytime, $rest) {
    $d = intdiv($time, $flytime + $rest) * $speed * $flytime;
    $rem = $time % ($flytime + $rest);
    $d += (($rem >= $flytime) ? $flytime : $rem) * $speed;

    return $d;
}

function part1(): void
{
    global $reindeer;
    $time = 2503;
    $dist = array();
    foreach ($reindeer as $r=>$data) {
        $dist[$r] = calculateDistance($time, $data["speed"], $data["fly"], $data["rest"]);
    }
    rsort($dist);
    echo "Part 1: Fastest reindeer traveled = ".$dist[0]."\n";
}

function part2(): void
{
    global $reindeer;
    $time = 2503;
    $dist = array();
    $max = 0;
    for ($t=1; $t<=$time; $t++) {
        foreach ($reindeer as $r => $data) {
            $dist[$r] = calculateDistance($t, $data["speed"], $data["fly"], $data["rest"]);
            if ($max < $dist[$r]) $max = $dist[$r];
        }
        arsort($dist);
        foreach ($dist as $r=>$d) {
            if ($d == $max) {
                $reindeer[$r]["points"]++;
            } else break;
        }
    }

    usort($reindeer, "cmp");
    echo "Part 2: Most points = ".$reindeer[0]["points"]."\n";
}
function cmp($a, $b)
{
    return $b["points"]<=>$a["points"];
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
