<?php

$input = 347991;
$dirs = [[0,1],[-1,0],[0,-1],[1,0]];

function spiral($n)
{
    $k=ceil((sqrt($n)-1)/2);
    $t=2*$k+1;
    $m=$t*$t;
    $t=$t-1;
    if ($n>=$m-$t) return [$k-($m-$n),-1*$k];
    else $m=$m-$t;
    if ($n>=$m-$t) return [-1*$k,-1*$k+($m-$n)];
    else $m=$m-$t;
    if ($n>=$m-$t) return [-1*$k+($m-$n),$k];
    else return [$k,$k-($m-$n-$t)];
}
function part1(): void
{
    global $input, $dirs;
    // calculate the ulam spiral
    $inc = 1;
    $coords=[0,0];
    $dir=0;
    for ($i=2; $i<=$input;) {
        for ($j=0; $j<2; $j++, $i+=$inc) {
            if ($i + $inc < $input) {
                $coords[0] += $dirs[$dir][0] * $inc;
                $coords[1] += $dirs[$dir][1] * $inc;
            } else {
                // add the number of moves we have left in this direction
                $coords[0] += $dirs[$dir][0] * ($input - $i + 1);
                $coords[1] += $dirs[$dir][1] * ($input - $i + 1);
                break 2;
            }
            $dir = ($dir+1)%4;
        }
        $inc++;
    }

    // use math to determine cartesian position of value on spiral
//    $coords = spiral($input);
    echo "Part 1: Steps = ".(abs($coords[0]) + abs($coords[1]))."\n";
}

function part2(): void
{
    global $input, $dirs;
    $inc = 1;
    $spiral = array("0,0"=>1);
    $coords=[0,0];
    $dir=0;
    $adjDirs = array_merge($dirs, [[-1,-1],[1,1],[1,-1],[-1,1]]);
    $i=0;
    do {
        $sum = 0;
        $coords[0] += $dirs[$dir][0];
        $coords[1] += $dirs[$dir][1];
        foreach ($adjDirs as $d)
            $sum+=$spiral[($coords[0] + $d[0]).",".($coords[1] + $d[1])]??0;
        $spiral[$coords[0].",".$coords[1]] = $sum;
        $i++;
        if ($i%$inc == 0)
            $dir = ($dir + 1) % 4;
        if ($i==$inc*($inc+1)) {
            $inc++;
        }
    } while ($sum < $input);

    echo "Part 2: Value = $sum\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
