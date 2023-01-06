<?php

const INT_MAX = 9223372036854775807;
const INT_MIN = -9223372036854775808;

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);
$elves = array();

function readInput(): void
{
    global $elves, $top, $bot, $left, $right;
    $lines = file('input.txt');
    foreach ($lines as $row=>$line) {
        $cols = str_split(trim($line));
        foreach ($cols as $col=>$d) {
            if ($d == "#") {
                $elves[$row.",".$col] = 0;
            }
        }
    }
}

function printGrid() {
    global $elves;
    $bot = $top = $left = $right = 0;

    foreach (array_keys($elves) as $e) {
        list ($row, $col) = explode(",",$e);
        if ($row < $top) $top = $row;
        else if ($row > $bot) $bot = $row;
        if ($col < $left) $left = $col;
        else if ($col > $right) $right = $col;
    }
    echo "$top $bot $left $right\n";
    $co = $ro = 0;
    if ($top < 0) $co = abs($top);
    if ($left < 0) $ro = abs($left);
    $grid = array();
    for ($i=0; $i<($bot-$top)+1; $i++)
        $grid[] = array_fill(0,($right-$left)+1, ".");
    foreach (array_keys($elves) as $e) {
        list ($row, $col) = explode(",",$e);
        $grid[$row+$ro][$col+$co]="#";
    }
    foreach($grid as $cols) {
        foreach ($cols as $square) {
            echo $square;
        }
        echo "\n";
    }

}
function performRound() {
    global $elves, $top, $bot, $left, $right;
    // first figure out where each elf wants to go
    $moves = array();
    $dirs = array();
    foreach ($elves as $coords=>$dir) {
        $found = false;
        $tries = 0;
        list($r, $c) = explode(",",$coords);
        $r = intval($r);
        $c = intval($c);
        $dirs[0] = isset($elves[($r-1).",".($c-1)]) || isset($elves[($r-1).",".($c)]) || isset($elves[($r-1).",".($c+1)]);
        $dirs[1] = isset($elves[($r+1).",".($c-1)]) || isset($elves[($r+1).",".($c)]) || isset($elves[($r+1).",".($c+1)]);
        $dirs[2] = isset($elves[($r-1).",".($c-1)]) || isset($elves[($r).",".($c-1)]) || isset($elves[($r+1).",".($c-1)]);
        $dirs[3] = isset($elves[($r-1).",".($c+1)]) || isset($elves[($r).",".($c+1)]) || isset($elves[($r+1).",".($c+1)]);
        do {
            // if any direction is true then we have adjacent neighbors
            if (in_array(true, $dirs)) {
//                echo "again examining elf at $r,$c dir $dir\n";
                switch ($dir) {
                    case 0:
                        // check above elf
                        if ($dirs[0]) {
                            // check next direction
                            $dir = ++$dir % 4;
                            $tries++;
                        } else {
                            $moves[($r - 1) . ",$c"][] = $coords;
                            $found = true;
                        }
                        break;
                    case 1:
                        // check below elf
                        if ($dirs[1]) {
                            // check next direction
                            $dir = ++$dir % 4;
                            $tries++;
                        } else {
                            $moves[($r + 1) . ",$c"][] = $coords;
                            $found = true;
                        }
                        break;
                    case 2:
                        // check left of elf
                        if ($dirs[2]) {
                            // check next direction
                            $dir = ++$dir % 4;
                            $tries++;
                        } else {
                            $moves["$r," . ($c - 1)][] = $coords;
                            $found = true;
                        }
                        break;
                    case 3:
                        // check right of elf
                        if ($dirs[3]) {
                            // check next direction
                            $dir = ++$dir % 4;
                            $tries++;
                        } else {
                            $moves["$r," . ($c + 1)][] = $coords;
                            $found = true;
                        }
                        break;
                }
            } else $found = true;
        } while (!$found && $tries<4);
        // update the first direction to look for the next round
        $elves[$coords] = ++$elves[$coords]%4;
    }

    // now move them if they can be moved
    foreach ($moves as $move=>$es) {
        if (count($es) == 1) {
            //echo "Moving ".$es[0]." to $move\n";
            // only one elf wants to move to this spot so move them
            $elves[$move] = $elves[$es[0]];
            unset($elves[$es[0]]);
        }
    }
    return (count($moves));
}

function part1(): void
{
    global $elves;

    printGrid();
    $bot = $right = 0;
    $top = $left = INT_MAX;
    for ($i=0; $i<10; $i++) {
        echo "Round ".($i+1)."\n";
        performRound();
        printGrid();
    }

    foreach (array_keys($elves) as $e) {
        list ($row, $col) = explode(",",$e);
        if ($row < $top) $top = $row;
        else if ($row > $bot) $bot = $row;
        if ($col < $left) $left = $col;
        else if ($col > $right) $right = $col;
    }

    $empty = (($bot-$top)+1) * (($right-$left)+1) - count($elves);

    echo "Part 1: Empty tiles = $empty\n";
}

function part2(): void
{
    $moves = 0;
    $round = 0;
    do {
        $round++;
        $moves = performRound();
    } while ($moves !=0);

    echo "Part 2: Rounds = $round\n";
}

readInput();
$start = microtime(true);
$backup = $elves;
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
$elves = $backup;
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
