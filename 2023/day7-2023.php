<?php

const FIVE_OF_A_KIND = 1;
const FOUR_OF_A_KIND = 2;
const FULL_HOUSE = 3;
const THREE_OF_A_KIND = 4;
const TWO_PAIR = 5;
const ONE_PAIR = 6;
const HIGH_CARD = 7;

$hands = array();
$cardRanks = array("2"=>1,
    "3"=>2,
    "4"=>3,
    "5"=>4,
    "6"=>5,
    "7"=>6,
    "8"=>7,
    "9"=>8,
    "T"=>9,
    "J"=>10,
    "Q"=>11,
    "K"=>12,
    "A"=>13);
function readInput() {
    global $hands;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $parts = explode(" ", trim($line));
        $cards = str_split($parts[0]);
        $hands[] = ["cards"=>$cards, "bid"=>$parts[1]];
    }
}

function getHandType($cards, $wildCard=null) {
    $counts = array_count_values($cards);
    arsort($counts);
    $keys = array_keys($counts);
    if (isset($wildCard)) {
        // update counts with wild card
        if ($keys[0] == $wildCard) {
            // the card with the most counts is wild so add it to the second most
            if (count($counts)>1) {
                $counts[$keys[1]] += $counts[$keys[0]];
                unset($counts[$keys[0]]);
            }
        } else for ($i=1; $i<count($counts); $i++) {
            if ($keys[$i] == $wildCard) {
                $counts[$keys[0]]+=$counts[$keys[$i]];
                unset($counts[$keys[$i]]);
                break;
            }
        }
    }
    switch (count($counts)) {
        case 1:
            $type = FIVE_OF_A_KIND;
            break;
        case 2:
            if (reset($counts) == 4) $type = FOUR_OF_A_KIND;
            else $type = FULL_HOUSE;
            break;
        case 3:
            if (reset($counts) == 3) $type = THREE_OF_A_KIND;
            else $type = TWO_PAIR;
            break;
        case 4:
            $type = ONE_PAIR;
            break;
        default:
            $type = HIGH_CARD;
            break;
    }
    return $type;
}

function handCompare($h1, $h2) {
    global $cardRanks;
    if ($h1["type"] > $h2["type"]) return -1;
    elseif ($h1["type"] < $h2["type"]) return 1;
    else {
        // hands are the same type, need to look at card order
        foreach ($h1["cards"] as $key=>$card) {
            if ($cardRanks[$card] > $cardRanks[$h2["cards"][$key]]) return 1;
            elseif ($cardRanks[$card] < $cardRanks[$h2["cards"][$key]]) return -1;
        }
    }

    // hands myst be equal
    return 0;
}
function part1() {
    global $hands;
    $total = 0;

    foreach ($hands as $key=>$hand) {
        $hands[$key]["type"] = getHandType($hand["cards"]);
    }
    usort($hands, "handCompare");
    foreach ($hands as $key=>$hand) {
        $total+=($key+1)*$hand["bid"];
    }

    print "Part 1: The total winnings for all hands is $total \n";
}

function part2() {
    global $hands, $cardRanks;
    $cardRanks["J"] = 0;
    $total = 0;

    foreach ($hands as $key=>$hand) {
        $hands[$key]["type"] = getHandType($hand["cards"], "J");
    }
    usort($hands, "handCompare");
    foreach ($hands as $key=>$hand) {
        $total+=($key+1)*$hand["bid"];
    }

    print "Part 2: The total winnings for all hands is $total \n";
}

readInput();
part1();
part2();
