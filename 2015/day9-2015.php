<?php

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);
$lines = file('input.txt');

//$dirs = str_split($in);
$dist = array();

foreach ($lines as $line) {
    $line = trim($line);
    $data = explode(" ", $line);

    if (!array_key_exists($data[0], $dist)) {
        $dist[$data[0]] = array();
    }
    $dist[$data[0]][$data[2]] = intval($data[4]);

    // add distance for reverse direction
    if (!array_key_exists($data[2], $dist)) {
        $dist[$data[2]] = array();
    }
    $dist[$data[2]][$data[0]] = intval($data[4]);
}

$most = 0;
foreach (array_keys($dist) as $city) {
    $d = findDistance($city);
    if ($d > $most) {
        $most = $d;
    }
}

function findDistance($city, $vis=array(), $distance=0) {
    global $dist, $total;
    $vis[] = $city;
    $most = 0;
    $end = true;
    foreach ($dist[$city] as $c=>$d) {
        if (!in_array($c, $vis)) {
            //echo "$city to $c = $d\n";
            $end = false;
            $m = findDistance($c, $vis, $distance+$d);
            if ($m>$most) {
                $most = $m;
            }
        }
    }
    if ($end) {
        return $distance;
    } else {
        return $most;
    }
}

echo "$most\n";
?>
