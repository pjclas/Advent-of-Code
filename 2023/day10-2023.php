<?php

function readInput(): void
{
    global $map, $start;
    $start = false;
    $lines = file('input.txt');
    foreach ($lines as $key=>$line) {
        if ($start === false) {
            $start = strpos($line, 'S');
            if ($start !== false) {
                $start = [$key, $start];
            }
        }
        $map[] = str_split(trim($line));
    }

    // now replace the symbol in the starting square with the appropriate pipe
    $startPipe = "";
    if ($start[0]>0 && strpos("|7F", $map[$start[0]-1][$start[1]]) !== false) $startPipe.="-10";
    if ($start[0]<(count($map)-1) && strpos("|LJ", $map[$start[0]+1][$start[1]]) !== false) $startPipe.="10";
    if ($start[1]>0 && strpos("-FL", $map[$start[0]][$start[1]-1]) !== false) $startPipe.="0-1";
    if ($start[1]<(count($map[0])-1) && strpos("-7J", $map[$start[0]][$start[1]+1]) !== false) $startPipe.="01";

    $pipeSymbols = ["0-101"=>'-',"-1010"=>'|',"-1001"=>'L',"-100-1"=>'J',"1001"=>'F',"100-1"=>'7'];
    $map[$start[0]][$start[1]] = $pipeSymbols[$startPipe];
}

function getNextPos($pos, $prevPos): mixed
{
    global $map;
    $newPos = match ($map[$pos[0]][$pos[1]]) {
        '-' => array([$pos[0], $pos[1] - 1], [$pos[0], $pos[1] + 1]),
        '|' => array([$pos[0] - 1, $pos[1]], [$pos[0] + 1, $pos[1]]),
        'L' => array([$pos[0] - 1, $pos[1]], [$pos[0], $pos[1] + 1]),
        'J' => array([$pos[0] - 1, $pos[1]], [$pos[0], $pos[1] - 1]),
        'F' => array([$pos[0] + 1, $pos[1]], [$pos[0], $pos[1] + 1]),
        '7' => array([$pos[0] + 1, $pos[1]], [$pos[0], $pos[1] - 1])
    };
    return ($prevPos === $newPos[0])?$newPos[1]:$newPos[0];
}

function part1(): void
{
    global $start;
    $pos=$prevPos=$start;
    $steps=0;
    do {
        $steps++;
        $nextPos = getNextPos($pos, $prevPos);
        $prevPos = $pos;
        $pos = $nextPos;
    } while ($pos !== $start);
    $steps = $steps/2;
    print "Part 1: The number of steps to furthest position is $steps\n";
}

function part2(): void
{
    global $map, $start;
    $pos=$prevPos=$start;
    $count=0;
    $loop = array_fill(0,count($map), array_fill(0,count($map[0]), '.'));
    do {
        $loop[$pos[0]][$pos[1]] = $map[$pos[0]][$pos[1]];
        $nextPos = getNextPos($pos, $prevPos);
        $prevPos = $pos;
        $pos = $nextPos;
    } while ($pos !== $start);
    foreach ($loop as $row=>$cols) {
        foreach ($cols as $col=>$tile) {
            if ($tile == '.') {
                // this is not in the loop, let's see if it's inside it by checking how many
                // lines we cross to get to the edge of the boundary
                // even means the tile is outside the loop
                // Ref: point-in-polygon algorithm
                preg_match_all('/[F|7]/', substr(implode("", $cols),$col), $matches);
                $count+=count($matches[0]) % 2;
            }
        }
    }
    print "Part 2: The number of tiles enclosed by the loop is $count\n";
}

readInput();
part1();
part2();
