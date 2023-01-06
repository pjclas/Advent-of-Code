<?php

function findFactors($num)
{
    $result = array();
    $i=1;
    while ($i*$i <=$num) {
        if ($num%$i == 0) {
            $result[] = $i;
            $div = $num/$i;
            if ($div != $i) {
                $result[] = $div;
            }
        }
        $i++;
    }
    return $result;
}

function part1(): void
{
    $p = 33100000/10;
    $house = 1;
    while (array_sum(findFactors($house)) < $p) {
        $house++;
    }

    echo "Part 1: House number = $house\n";
}

function part2(): void
{
    $p = 33100000/11;
    $house = 0;
    do {
        $house++;
        $sum = 0;
        $factors = findFactors($house);
        foreach($factors as $f) {
            if ($f*50 >= $house) $sum+=$f;
        }
    } while ($sum < $p);

    echo "Part 2: House number = $house\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
