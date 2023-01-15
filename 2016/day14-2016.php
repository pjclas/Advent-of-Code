<?php

$salt = "yjdafjpo";
$hashes = array();

function getHash($index, $loop)
{
    global $hashes, $salt;
    if (!array_key_exists($index, $hashes)) {
        $hashes[$index] = $salt . $index;
        for ($i = 0; $i < $loop; $i++) {
            $hashes[$index] = md5($hashes[$index]);
        }
    }
    return $hashes[$index];
}

function getHashIndex($numHashes, $loop=1): int
{
    $index = 0;
    while ($numHashes != 0) {
        preg_match('/(.)\1{2}/', getHash($index, $loop), $matches);
        if (count($matches) > 0) {
            for ($i=$index+1; $i<$index+1001; $i++) {
                if (str_contains(getHash($i, $loop),str_repeat($matches[0][0], 5))) {
                    $numHashes--;
                    break;
                }
            }
        }
        $index++;
    }

    return $index-1;
}
function part1(): void
{
    $index = getHashIndex(64);
    echo "Part 1: Index of 64th hash is $index\n";
}

function part2(): void
{
    global $hashes;
    $hashes = array();
    $index = getHashIndex(64, 2017);
    echo "Part 2: Index of 64th hash is $index\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
