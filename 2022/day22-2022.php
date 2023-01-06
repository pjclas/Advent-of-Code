<?php

const INT_MAX = 9223372036854775807;
const INT_MIN = -9223372036854775808;

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);

$grove = array();
$moves = array();
$dirs = [[0,1],[1,0],[0,-1],[-1,0]];

function readInput(): void
{
    global $grove, $moves;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        if (preg_match("!(\.|#)!", $line)) {
            $grove[] = str_split(rtrim($line));
        } else if (trim($line) != "") {
            preg_match_all("![LR]|\d+!", (trim($line)), $moves);
            $moves = $moves[0];
        }
    }
}

function getNextSide($row, $col, $dir) {
    echo "Getting next side for ".($row+1).", ".($col+1)."\n";
    // moving up
    // r, d, l, u
    if ($dir == 3) {
        if ($col < 50) {
            $row = $col+50;
            $col = 50;
            $dir = 0;
        } else if ($col < 100) {
            $row = $col+100;
            $col = 0;
            $dir = 0;
        } else {
            $row = 199;
            $col = $col-100;
            $dir = 3;
        }
    // moving down
    } else if ($dir == 1) {
        if ($col < 50) {
            $row = 0;
            $col = $col+100;
            $dir = 1;
        } else if ($col < 100) {
            $row = $col+100;
            $col = 49;
            $dir = 2;
        } else {
            $row = $col-50;
            $col = 99;
            $dir = 2;
        }
    // moving right
    } else if ($dir == 0) {
        if ($row < 50) {
            $row = 100+(49-$row);
            $col = 99;
            $dir = 2;
        } else if ($row < 100) {
            $col = $row+50;
            $row = 49;
            $dir = 3;
        } else if ($row < 150) {
            $col = 149;
            $row = 149-$row;
            $dir = 2;
        } else {
            $col = $row-100;
            $row = 149;
            $dir = 3;
        }
    // moving left
    } else {
        if ($row < 50) {
            $row = 100+(49-$row);
            $col = 0;
            $dir = 0;
        } else if ($row < 100) {
            $col = $row-50;
            $row = 100;
            $dir = 1;
        } else if ($row < 150) {
            $col = 50;
            $row = 149-$row;
            $dir = 0;
        } else {
            $col = $row-100;
            $row = 0;
            $dir = 1;
        }
    }

    return [$row, $col, $dir];
}

function part1(): void
{
    global $grove, $moves, $dirs;
    $pos = [0,array_search(".", $grove[0])];
    $dir = 0;  // row, column
    foreach ($moves as $m) {
        if ($m != 0) {
            switch ($m) {
                case 'L':
                    $dir = ($dir + 3) % 4;
                    echo "Turn $m, new direction = $dir\n";
                    break;
                case 'R':
                    $dir = ($dir + 1) % 4;
                    echo "Turn $m, new direction = $dir\n";
                    break;
                default:
                    echo "Move $m spaces.\n";
                    $wall = false;
                    $row = $pos[0];
                    $col = $pos[1];
                    do {
                        $col = ($col + $dirs[$dir][1])%count($grove[$pos[0]]);
                        echo "col=$col count=".count($grove[$pos[0]])."\n";
                        if ($col<0) $col += count($grove[$row]);
                        $row = ($row + $dirs[$dir][0])%count($grove);
                        echo "row=$row count=".count($grove)."\n";
                        if ($row<0) $row += count($grove);
                        echo "Checking $row, $col = \n";
                        if (isset($grove[$row][$col])) {
                            echo $grove[$row][$col]."\n";
                            if ($grove[$row][$col] == "#") {
                                // hit a wall, stop
                                $wall = true;
                            } else if ($grove[$row][$col] == ".") {
                                // we were able to move forward
                                $pos = [$row, $col];
                                $m--;
                                echo "m=$m\n";
                            }
                        }
                    } while ($wall == false && $m != 0);
                    echo "New position is ".($pos[0]+1).",".($pos[1]+1)."\n";
                    break;
            }
        }
    }
    echo "Part 1: Password = ".(1000*($pos[0]+1) + 4*($pos[1]+1) + $dir)."\n";
}

function part2(): void
{
    global $grove, $moves, $dirs;
    $pos = [0,array_search(".", $grove[0])];
    $dir = 0;  // row, column
    foreach ($moves as $m) {
        if ($m != 0) {
            switch ($m) {
                case 'L':
                    $dir = ($dir + 3) % 4;
                    echo "Turn $m, new direction = $dir\n";
                    break;
                case 'R':
                    $dir = ($dir + 1) % 4;
                    echo "Turn $m, new direction = $dir\n";
                    break;
                default:
                    echo "Move $m spaces.\n";
                    $wall = false;
                    $row = $pos[0];
                    $col = $pos[1];
                    do {
                        $col += $dirs[$dir][1];
//                        echo "col=$col count=".count($grove[$pos[0]])."\n";
//                        if ($col<0) $col += count($grove[$row]);
                        $row += $dirs[$dir][0];
//                        echo "row=$row count=".count($grove)."\n";
//                        if ($row<0) $row += count($grove);
                        echo "Checking ".($row+1).", ".($col+1)." = \n";
                        if (!isset($grove[$row][$col]) || $grove[$row][$col] == " ") {
                            list($r, $c, $d) = getNextSide($row - $dirs[$dir][0], $col - $dirs[$dir][1], $dir);
                            echo "Next side pos =  ".($r+1).", ".($c+1)." = ".$grove[$r][$c]."\n";
                            if ($grove[$r][$c] == ".") {
                                $row = $r;
                                $col = $c;
                                $dir = $d;
                                $pos = [$row, $col];
                                $m--;
                            } else {
                                $wall = true;
                            }
                        } else {
                            echo $grove[$row][$col]."\n";
                            if ($grove[$row][$col] == "#") {
                                // hit a wall, stop
                                $wall = true;
                            } else {
                                // we were able to move forward
                                $pos = [$row, $col];
                                $m--;
                                echo "m=$m\n";
                            }
                        }
                    } while ($wall == false && $m != 0);
                    echo "New position is ".($pos[0]+1).",".($pos[1]+1)."\n";
                    break;
            }
        }
    }
    echo "Part 2: Password = ".(1000*($pos[0]+1) + 4*($pos[1]+1) + $dir)."\n";
}

readInput();
$start = microtime(true);
//part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";
