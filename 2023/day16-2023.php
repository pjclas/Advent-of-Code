<?php

$dirs = ['n'=>[-1,0],'e'=>[0,1],'s'=>[1,0],'w'=>[0,-1]];
function readInput() {
    global $tiles;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $tiles[] = str_split(trim($line));
    }
}

function getEnergizedTiles(&$energized, $dir='e', $pos=[0,0], &$cache=array()) {
    global $tiles, $dirs;

    if (!isset($tiles[$pos[0]][$pos[1]])) return;
    $tile = $tiles[$pos[0]][$pos[1]];
    // we can treat n/s the same when on - and e/w the same when on |
    if ($tile == '-' && $dir == 's') $dir = 'n';
    if ($tile == '|' && $dir == 'w') $dir = 'e';
    $posStr = implode(",", $pos);
    $key = $dir.$posStr;

    // check if we've been here before from this direction or if we are out of the device boundaries
    if (array_key_exists($key,$cache)) return;
    // add position to cache
    $cache[$key] = 1;
    $energized[$posStr] = 1;

    // check if we just keep moving forward
    if ($tile == "." ||
        ($tile=='|' && ($dir=='n' || $dir=='s')) ||
        ($tile=='-' && ($dir=='e' || $dir=='w'))) {
        getEnergizedTiles($energized, $dir, [$pos[0] + $dirs[$dir][0], $pos[1] + $dirs[$dir][1]], $cache);
    } else {
        switch ($tile) {
            case '|':
                getEnergizedTiles($energized, 'n', [$pos[0] + $dirs['n'][0], $pos[1] + $dirs['n'][1]], $cache);
                getEnergizedTiles($energized, 's', [$pos[0] + $dirs['s'][0], $pos[1] + $dirs['s'][1]], $cache);
                break;
            case '-':
                getEnergizedTiles($energized, 'e', [$pos[0] + $dirs['e'][0], $pos[1] + $dirs['e'][1]], $cache);
                getEnergizedTiles($energized, 'w', [$pos[0] + $dirs['w'][0], $pos[1] + $dirs['w'][1]], $cache);
                break;
            case '\\':
                $newDir = match ($dir) {
                    'n' => 'w',
                    'e' => 's',
                    's' => 'e',
                    'w' => 'n'
                };
                getEnergizedTiles($energized, $newDir, [$pos[0] + $dirs[$newDir][0], $pos[1] + $dirs[$newDir][1]], $cache);
            break;
            case '/':
                $newDir = match ($dir) {
                    'n' => 'e',
                    'e' => 'n',
                    's' => 'w',
                    'w' => 's'
                };
                getEnergizedTiles($energized, $newDir, [$pos[0] + $dirs[$newDir][0], $pos[1] + $dirs[$newDir][1]], $cache);
                break;
        }
    }
}

function part1() {
    $energized = array();
    getEnergizedTiles($energized);
    print "Part 1: The number of energized tiles is ".count($energized)."\n";
}

function part2() {
    global $tiles;
    $maxEnergy = 0;
    foreach (range(0,count($tiles)-1) as $row) {
        $energized = array();
        getEnergizedTiles($energized,'e',[$row,0]);
        if (count($energized) > $maxEnergy) $maxEnergy = count($energized);
        $energized = array();
        getEnergizedTiles($energized,'w',[$row,count($tiles[0])-1]);
        if (count($energized) > $maxEnergy) $maxEnergy = count($energized);
    }
    foreach (range(0,count($tiles[0])-1) as $col) {
        $energized = array();
        getEnergizedTiles($energized,'s',[0,$col]);
        if (count($energized) > $maxEnergy) $maxEnergy = count($energized);
        $energized = array();
        getEnergizedTiles($energized,'n',[count($tiles)-1, $col]);
        if (count($energized) > $maxEnergy) $maxEnergy = count($energized);
    }
    print "Part 2: The max number of energized tiles is $maxEnergy\n";
}

readInput();
part1();
part2();
