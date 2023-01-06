<?php

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);
$lines = file('input.txt');

function part1() {
    global $lines;
    $total = 0;
    foreach ($lines as $line) {
        preg_match_all('!-?\d+!', $line, $data);
        foreach ($data[0] as $num) {
            $total += $num;
        }
    }

    return $total;
}

function part2() {
    global $lines;
    $total = 0;
    foreach ($lines as $line) {
        $json = json_decode($line);
        $total += countNums($json);
    }

    return $total;
}

function countNums($data) {
    $total = 0;
    if (is_object($data)) {
        $sum = 0;
        foreach ($data as $k=>$p) {
            $val = countNums($p);
            if ($val === false) {
                $sum = 0;
                break;
            } else {
                $sum += $val;
            }
        }
        $total += $sum;                
    } else if (is_array($data)) {
        foreach ($data as $p) {
            $total += intval(countNums($p));
        }
    } else {
        if (str_contains("red", $data))
            return false;
        else if (is_numeric($data))
            return intval($data);
        else
            return 0;
    }

    return $total;
}

echo part1()."\n";
echo part2()."\n";
?>
