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
    $hist = array();
    while (array_key_exists($i, $instructions)) {
        $hist[$i] = $r;
        switch ($instructions[$i][0]) {
            case "cpy":
                // tgl may have made this command invalid, need to check if second param is a register
                if (!is_numeric($instructions[$i][2])) {
                    $r[$instructions[$i][2]] = is_numeric($instructions[$i][1]) ? intval($instructions[$i][1]) : $r[$instructions[$i][1]];
                }
                $i++;
                break;
            case "inc":
                // tgl may have made this command invalid, need to check if second param is a register
                if (!is_numeric($instructions[$i][1]))
                    $r[$instructions[$i][1]]++;
                $i++;
                break;
            case "dec":
                // tgl may have made this command invalid, need to check if second param is a register
                if (!is_numeric($instructions[$i][1]))
                    $r[$instructions[$i][1]]--;
                $i++;
                break;
            case "jnz":
                $jumpVal = is_numeric($instructions[$i][2])?intval($instructions[$i][2]):$r[$instructions[$i][2]];
                if (is_numeric($instructions[$i][1])) {
                    if ($instructions[$i][1] != 0)
                        $i+=$jumpVal;
                    else
                        $i++;
                    break;
                } else $condVar = $r[$instructions[$i][1]];
                if ($jumpVal > 0) {
                    $i += $jumpVal;
                } else if ($jumpVal < 0) {
                    // calculate the change in registers based on history
                    $startPos = $i + $jumpVal;
                    $r["a"] += ($r["a"] - $hist[$startPos]["a"]) * $condVar;
                    $r["b"] += ($r["b"] - $hist[$startPos]["b"]) * $condVar;
                    $r["c"] += ($r["c"] - $hist[$startPos]["c"]) * $condVar;
                    $r["d"] += ($r["d"] - $hist[$startPos]["d"]) * $condVar;
                    $i++;
                } else {
                    echo "Invalid infinite loop, exiting...\n";
                    exit(1);
                }
                break;
            case "tgl":
                $newIndex = $i+(is_numeric($instructions[$i][1])?intval($instructions[$i][1]):$r[$instructions[$i][1]]);
                if (array_key_exists($newIndex, $instructions)) {
                    switch ($instructions[$newIndex][0]) {
                        case "inc":
                            $instructions[$newIndex][0] = "dec";
                            break;
                        case "jnz":
                            $instructions[$newIndex][0] = "cpy";
                            break;
                        default:
                            if (count($instructions[$newIndex]) == 2)
                                $instructions[$newIndex][0] = "inc";
                            else
                                $instructions[$newIndex][0] = "jnz";
                    }
                }
                $i++;
                break;
        }
    }

    return $r;
}

function part1(): void
{
    global $instructions;
    $r = processInstructions($instructions, 7);

    echo "Part 1: a = ".$r["a"]."\n";
}

function part2(): void
{
    global $instructions;
    $r = processInstructions($instructions, 12);
    echo "Part 2: a = ".$r["a"]."\n";
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
