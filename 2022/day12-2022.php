<?php

define("INT_MAX", 0x7FFFFFFF);

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);
$lines = file('input.txt');

$grid = array();
$s = array();
$e = array();
$rMove = [1,0,-1,0];
$cMove = [0,1,0,-1];
$moves = array();
$dist = array();

foreach ($lines as $r=>$line) {
    $grid[] = str_split(trim($line));
    $dist[] = array_fill(0, count($grid[$r]), INT_MAX);

    $c = strpos($line, 'S');
    if ($c !== false) {
        $s = [$r,$c];
        $grid[$r][$c] = 'a';
    }
    $c = strpos($line, 'E');
    if ($c !== false) {
        $e = [$r,$c]; 
        $grid[$r][$c] = 'z';
    }
}

function shortestBFPath($s, $e) {
    global $grid,$rMove,$cMove,$dist,$moves;
    list($r,$c) = $s;
    $moves[] = array("moves"=>0,
                     "coords"=>$s);
    do {
        $m = array_pop($moves);
        list($row,$col) = $m["coords"];
        foreach(range(0,3) as $i) {
            $r = $row + $rMove[$i];
            $c = $col + $cMove[$i];
            if ($r>=0 && $r<count($grid) && $c>=0 && $c<count($grid[0]) &&
//                ord($grid[$r][$c]) >= ord($grid[$row][$col])-1) {
                ord($grid[$r][$c]) <= ord($grid[$row][$col])+1) {
                if ($m["moves"]+1 < $dist[$r][$c]) {
                    $moves[] = array("moves"=>($m["moves"]+1),
                                     "coords"=>[$r,$c]);
                    $dist[$r][$c] = $m["moves"]+1;
                }
            }
        }

        usort($moves, fn($a, $b) => $b["moves"] <=> $a["moves"]);
//    } while ($grid[$m["coords"][0]][$m["coords"][1]] != 'a' && count($moves)!= 0);
    } while ($m["coords"] != $e && count($moves)!= 0);

    return $m["moves"];
}

function shortestDFPath($s, $e, $moves=0) {
    global $grid,$rMove,$cMove,$dist;

    $m = INT_MAX;
    list($r, $c) = $s;
    $dist[$r][$c] = $moves;

    if ($s == $e) {
        echo "Found the exit in $moves\n";
        return $moves;
    }

    foreach(range(0,3) as $i) {
        $rr = $r + $rMove[$i];
        $cc = $c + $cMove[$i];
        if ($rr>=0 && $rr<count($grid) && $cc>=0 && $cc<count($grid[0]) &&
            ord($grid[$rr][$cc]) <= ord($grid[$r][$c])+1 &&
            $moves+1 < $dist[$rr][$cc]) {
            $m = min($m, shortestDFPath([$rr, $cc], $e, $moves+1));
        }
    }

    return $moves+$m;
}

echo shortestDFPath($s, $e);

?>
