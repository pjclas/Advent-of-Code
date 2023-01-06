<?php

const INT_MAX = 9223372036854775807;
const INT_MIN = -9223372036854775808;

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);
$data = array();

function readInput(): void
{
    global $data;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $data[] = str_split(trim($line));
    }
}

function createSnafu($num) {
    $snafu = array();
    $digits=["="=>-2,"-"=>-1,"0"=>0,"1"=>1,"2"=>2];

    // first, find the most significant snafu place
    $mult = 1;
    $sum=0;
    $max = array();
    while ($num>2*$mult+$sum) {
        $sum+=2*$mult;
        $max[$mult] = $sum;
        $mult*=5;
    }

    // next, work our way down to second least significant place filling in digits
    $sum = 0;
    for ($m=$mult; $m>1; $m/=5) {
        $nd = "=";
        foreach ($digits as $d=>$v) {
            $nd=$d;
            if ($sum+$m*$v + $max[$m/5] >= $num) {
                break;
            }
        }
        $snafu[] = $nd;
        $sum+=$m*$digits[$nd];
    }

    // finally, add the last digit
    foreach ($digits as $k=>$v) {
        if ($sum+$v == $num) {
            $snafu[] = $k;
        }
    }

    return implode("",$snafu)."\n";
}

function part1($data): void
{
    $sum = 0;
    foreach ($data as $number) {
        $mult=1;
        $num = 0;
        foreach (array_reverse($number) as $d) {
            switch($d) {
                case "=":
                    $num+=-2*$mult;
                    break;
                case "-":
                    $num+=-1*$mult;
                    break;
                default:
                    $num+=$d*$mult;
                    break;
            }
            $mult*=5;
        }
        $sum += $num;
    }
    echo "Part 1: Sum = ".createSnafu($sum)."\n";
}

readInput();
$start = microtime(true);
part1($data);
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
