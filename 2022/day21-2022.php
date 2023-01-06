<?php
const INT_MAX = 9223372036854775807;

$monkeys = array();
$n1 = $n2 = 0;
function readInput(): void
{
    global $monkeys, $n1, $n2;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $monkey = explode(": ", trim($line));
        $f = explode(" ", $monkey[1]);
        if (count($f)>1) {
            $monkeys[$monkey[0]] = "return (is_numeric(\$monkeys['$f[0]']) && is_numeric(\$monkeys['$f[2]']))?\$monkeys['$f[0]'] $f[1] \$monkeys['$f[2]']:false;";
            if ($monkey[0] == "root") {
                $n1 = $f[0];
                $n2 = $f[2] ;
            }
        } else {
            $monkeys[$monkey[0]] = $f[0];
        }
    }
}

function part1(): void
{
    global $monkeys;
    while (!is_numeric($monkeys["root"])) {
       // print_r($monkeys);
        foreach ($monkeys as $m=>$f) {
            if (!is_numeric($f)) {
                $ans = eval($f);
                if ($ans !== false) {
                    $monkeys[$m] = $ans;
                }
            }
        }
    }

    echo "Part 1: Monkey 'root' answer is ".$monkeys['root']."\n";
}

function part2(): void
{
    global $monkeys, $backup, $n1, $n2;
    $root = str_replace("+", "==",$monkeys["root"]);

    $low = 0;
    $high = INT_MAX;
    do {
        $monkeys = $backup;
        $monkeys["humn"] = floor($low + ($high - $low) / 2);
        while (!is_numeric($monkeys["root"])) {
            // print_r($monkeys);
            foreach ($monkeys as $m=>$f) {
                if (!is_numeric($f)) {
                    $ans = eval($f);
                    if ($ans !== false) {
                        $monkeys[$m] = $ans;
                    }
                }
            }
        }
        if (!eval($root)) {
            if ($monkeys[$n1] < $monkeys[$n2])
                $high = $monkeys["humn"] - 1;
            else
                $low = $monkeys["humn"] + 1;
        }
    } while (!eval($root));

    echo "Part 2: Monkey 'humn' answer is ".$monkeys['humn']."\n";
}

readInput();
$backup = $monkeys;
part1();
$monkeys = $backup;
part2();


