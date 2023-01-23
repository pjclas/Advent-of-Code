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
function processInstructions($instructions, $a)
{
    $i = 0;
    $r = ["a"=>$a, "b"=>0, "c"=>0, "d"=>0];
    $outVal = "";
    $count=0;
    while (array_key_exists($i, $instructions) && $count<100000) {
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
            case "out":
                $outVal .= (string)is_numeric($instructions[$i][1])?intval($instructions[$i][1]):$r[$instructions[$i][1]];
                //echo "$outVal\n";
                $i++;
                break;
        }
        $count++;
    }

    if (strlen($outVal)%2 == 0) return $outVal;
    else return (substr($outVal,0,-1));
}

function part1(): void
{
    global $instructions;
    $a=0;
    $found = false;
    do {
        if (preg_match('/^(01)+$/', processInstructions($instructions, ++$a))) $found = true;
    } while (!$found);
    echo "Part 1: a = $a\n";
}

readInput();
$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
