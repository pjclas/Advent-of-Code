<?php

$instructions = array();
$bots = array();
$output = array();
function readInput(): void
{
    global $instructions;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $instructions[] = explode(" ", trim($line));
    }
}

function executeInstructions($instructions, $values=array()) {
    global $bots, $output;
    $nextInstructions = array();
    foreach ($instructions as $ins) {
        if ($ins[0] == "value") {
            $bots[$ins[5]][] = $ins[1];
        } else if (array_key_exists($ins[1], $bots) && count($bots[$ins[1]])==2) {
            $bot = $bots[$ins[1]];
            sort($bot);
            if ($bot == $values) return $ins[1];
            if ($ins[5] == "bot")
                $bots[$ins[6]][] = $bot[0];
            else
                $output[$ins[6]] = $bot[0];
            if ($ins[10] == "bot")
                $bots[$ins[11]][] = $bot[1];
            else
                $output[$ins[11]] = $bot[1];
            unset($bots[$ins[1]]);
        } else {
            $nextInstructions[] = $ins;
        }
    }
    return $nextInstructions;
}

function part1(): void
{
    global $instructions;
    $botInstructions = $instructions;
    while (is_array($botInstructions) && count($botInstructions) != 0) $botInstructions = executeInstructions($botInstructions, [17,61]);

    echo "Part 1: Bot that compares is #".$botInstructions."\n";
}

function part2(): void
{
    global $instructions, $output;
    $botInstructions = $instructions;
    while (is_array($botInstructions) && count($botInstructions) != 0) $botInstructions = executeInstructions($botInstructions);

    echo "Part 2: Output product = ".($output[0]*$output[1]*$output[2])."\n";
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
