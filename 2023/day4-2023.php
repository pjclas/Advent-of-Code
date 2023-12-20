<?php

$cards = array();

function readInput() {
    global $cards;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $line = preg_replace(['/.*: /','/ +/'], ['',' '], $line);
        $line = explode("|", $line);
        $cards[] = ["win"=>explode(" ", trim($line[0])), "elf"=>explode(" ", trim($line[1]))];
    }
}

function part1() {
    global $cards;
    $total = 0;
    foreach ($cards as $card) {
        $matched = array_intersect($card["win"], $card["elf"]);
        if (count($matched) > 0)
            $total+= 1<<(count($matched)-1);
    }

    print "Part 1: The sum of IDs of possible games is $total\n";
}

function part2() {
    global $cards;
    $counts = array_fill(0,count($cards),1);
    foreach ($cards as $key=>$card) {
        $matched = array_intersect($card["win"], $card["elf"]);
        $value = count($matched);
        for ($index=1; $index<=$value && $index<count($cards); $index++) {
            // increase counts for cards we won
            $counts[$key+$index]+=$counts[$key];
        }
    }

    print "Part 2: The number of cards we have is ".array_sum($counts)."\n";
}

readInput();
part1();
part2();

?>
