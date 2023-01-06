<?php

$instructions = array();

function readInput(): void
{
    global $instructions;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $instructions[] = explode(" ", str_replace(",", "", trim($line)));
    }
}

function processInstructions($instructions, $a=0)
{
    $i = 0;
    $r = ["a"=>$a, "b"=>0];
    while (array_key_exists($i, $instructions)) {
        switch ($instructions[$i][0]) {
            case "hlf":
                $r[$instructions[$i][1]] = intdiv($r[$instructions[$i][1]], 2);
                $i++;
                break;
            case "tpl":
                $r[$instructions[$i][1]]*=3;
                $i++;
                break;
            case "inc":
                $r[$instructions[$i][1]]++;
                $i++;
                break;
            case "jmp":
                $i+=intval($instructions[$i][1]);
                break;
            case "jie":
                if ($r[$instructions[$i][1]]%2 == 0)
                    $i+=intval($instructions[$i][2]);
                else $i++;
                break;
            case "jio":
                if ($r[$instructions[$i][1]] == 1)
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

    echo "Part 1: b = ".$r["b"]."\n";
}

function part2(): void
{
    global $instructions;
    $r = processInstructions($instructions, 1);

    echo "Part 1: b = ".$r["b"]."\n";
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
