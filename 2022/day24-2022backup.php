<?php

const INT_MAX = 9223372036854775807;
const INT_MIN = -9223372036854775808;

$wind = array();
$right = $bottom = 0;
$s = $f = array();

function readInput(): void
{
    global $wind, $bottom, $right, $s, $f;
    $lines = file('input.txt');
    $bottom = count($lines)-1;
    $right = count(str_split(trim($lines[$bottom])))-1;
    $s = [0,strpos($lines[0], ".")];
    $f = [$bottom,strpos($lines[$bottom], ".")];
    foreach ($lines as $r=>$line) {
        $cols = str_split(trim($line));
        foreach ($cols as $c=>$d) {
            if ($d!='.' && $d!='#') {
                $wind[0][$r.",".$c][] = $d;
            }
        }
    }

    $lcm = findlcm([$right-1,$bottom-1], 2);
    // calculate all the blizzard positions
    foreach (range(0,$lcm-2) as $i) {
        $newWind = array();
        foreach ($wind[$i] as $p => $w) {
            list($r, $c) = explode(",", $p);
            $r = intval($r);
            $c = intval($c);
            foreach ($w as $dir) {
                switch ($dir) {
                    case ">":
                        $nr = $r;
                        $nc = $c < ($right - 1) ? $c + 1 : 1;
                        break;
                    case "<":
                        $nr = $r;
                        $nc = ($c > 1) ? $c - 1 : $right - 1;
                        break;
                    case "^":
                        $nr = ($r > 1) ? $r - 1 : $bottom - 1;
                        $nc = $c;
                        break;
                    case "v":
                        $nr = $r < ($bottom - 1) ? $r + 1 : 1;
                        $nc = $c;
                        break;
                }
                $newWind[$nr . "," . $nc][] = $dir;
            }
        }
        ksort($newWind);
        $wind[$i+1] = $newWind;
    }
}

function gcd($a, $b)
{
    if ($b == 0)
        return $a;
    return gcd($b, $a % $b);
}

function findlcm($arr, $n)
{
    // Initialize result
    $ans = $arr[0];

    // ans contains LCM of
    // arr[0], ..arr[i]
    // after i'th iteration,
    for ($i = 1; $i < $n; $i++)
        $ans = ((($arr[$i] * $ans)) /
            (gcd($arr[$i], $ans)));

    return $ans;
}

function findPath($s,$f,$wi=0) {
    global $right,$bottom,$wind;

    $q[] = ["moves"=>0,"pos"=>$s,"wind"=>$wi];
    $dirs = [[-1,0],[1,0],[0,-1],[0,1],[0,0]];
    $moves = array();
    while (count($q) != 0) {
        $v = array_shift($q);
        if ($v["pos"] == $f) {
            return $v;
        }

        $wi = $v["wind"];
        // check if we've been in this position at an earlier time
        $m = "$wi:".implode(",",$v["pos"]);
        if (array_key_exists($m, $moves)) {
            continue;
        }
        else {
            $moves[$m] = $v["moves"];
        }

        // now check directions we can take safely
        $nwi = ($wi+1)%count($wind);
        foreach ($dirs as $dir) {
            $r = $v["pos"][0]+$dir[0];
            $c = $v["pos"][1]+$dir[1];
            if (($r==$s[0] && $c==$s[1]) ||  // at the start (may be strategic)
                ($r==$f[0] && $c==$f[1]) ||  // at the finish (this is our goal)
                ($r>0 && $r<$bottom && $c>0 && $c<$right && !array_key_exists($r.",".$c,$wind[$nwi]))) {
                // add this move to our queue
                $q[] = ["moves"=>$v["moves"]+1, "pos"=>[$r,$c], "wind"=>$nwi];
            }
        }
    }
}

function part1(): void
{
    global $s,$f;
    $m = findPath($s,$f);
    echo "Part 1: Number of moves = ".$m["moves"]."\n";
}

function part2(): void
{
    global $s, $f;
    $m=0;
    $v = findPath($s,$f);
    $m += $v["moves"];
    echo "m = $m\n";
    $v = findPath($f,$s,$v["wind"]);
    $m += $v["moves"];
    echo "m = $m\n";
    $v = findPath($s,$f,$v["wind"]);
    $m += $v["moves"];

    echo "Part 2: Number of moves = $m\n";
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
