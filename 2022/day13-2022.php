<?php

$INT_MAX = 0x7FFFFFFF;

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);

$l = $r = array();

function readInput() {
    global $l, $r;
    $lines = file('input.txt');
    for ($i=0; $i<count($lines); $i+=3) {
        eval('$l[]='.trim($lines[$i]).";");
        eval('$r[]='.trim($lines[$i+1]).';');
    }
}

function part1() {
    global $l, $r;
    $vsum = 0;
    for ($i=0; $i<count($l); $i++) {
        if (verifyNodeOrder($l[$i], $r[$i])) {
            $vsum += $i+1;
        }
    }

    echo "Part 1: Sum = $vsum\n";
}

function part2() {
    global $l, $r;
    $div1 = [[2]];
    $div2 = [[6]];
    $packets = array_merge($l,$r);
    $packets[] = $div1;
    $packets[] = $div2;

    usort($packets, "cmp");

    foreach ($packets as $k=>$p) {
        if ($p == $div1) $div1 = $k+1;
        else if ($p == $div2) $div2 = $k+1;
    }
    echo "Part 2: Decoder key = ".($div1*$div2)."\n";
}

function cmp($a, $b)
{
    if ($a == $b) {
        return 0;
    }
    return (verifyNodeOrder($a, $b)) ? -1 : 1;
}

function verifyNodeOrder($l, $r) {
    for ($i=0; $i<max(count($l),count($r)); $i++) {
        // check if index exists in both arrays
        if (!array_key_exists($i, $l)) return true;
        else if (!array_key_exists($i, $r)) return false;

        if (is_array($l[$i]) && !is_array($r[$i]))
            $r[$i] = [$r[$i]];
        if (!is_array($l[$i]) && is_array($r[$i]))
            $l[$i] = [$l[$i]];

        // now compare children
        if (is_array($l[$i])) {
            $ret = verifyNodeOrder($l[$i],$r[$i]);
            if ($ret === false || $ret === true) return $ret;
        } else if ($l[$i] == $r[$i]) {
            // check next item
            continue;
        } else {
//            echo "comparing ".$l[$i]." to ".$r[$i]." = ".($r[$i] <=> $l[$i])."\n";
            return ($l[$i] < $r[$i]);
        }
    }
}

readInput();
part1();
part2();

?>
