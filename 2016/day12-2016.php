<?php

$instructions = array();

function readInput(): void
{
    global $instructions;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $instructions[] = explode(" ", trim($line));
    }
}

function processInstructions($instructions, $c=0)
{
    $i = 0;
    $r = ["a"=>0, "b"=>0, "c"=>$c, "d"=>0];
    while (array_key_exists($i, $instructions)) {
        switch ($instructions[$i][0]) {
            case "cpy":
                $r[$instructions[$i][2]] = is_numeric($instructions[$i][1])?intval($instructions[$i][1]):$r[$instructions[$i][1]];
                $i++;
                break;
            case "inc":
                $r[$instructions[$i][1]]++;
                $i++;
                break;
            case "dec":
                $r[$instructions[$i][1]]--;
                $i++;
                break;
            case "jnz":
                if (is_numeric($instructions[$i][1])?intval($instructions[$i][1]):$r[$instructions[$i][1]] != 0)
                    $i+=intval($instructions[$i][2]);
                else $i++;
                break;
        }
    }

    return $r;
}

function part1(): void
{
    global $instructions;
    $r = processInstructions($instructions);

    echo "Part 1: a = ".$r["a"]."\n";
}

function part2(): void
{
    global $instructions;
    $r = processInstructions($instructions, 1);

    echo "Part 1: a = ".$r["a"]."\n";
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
