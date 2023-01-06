<?php

const INT_MAX = 2147483647;

//$costs = [["ore"=>["ore"=>4],"clay"=>["ore"=>2],"obsidian"=>["ore"=>3, "clay"=>14],"geode"=>["ore"=>2, "obsidian"=>7]]];
//$costs =[["ore"=>["ore"=>2],"clay"=>["ore"=>3],"obsidian"=>["ore"=>3, "clay"=>8],"geode"=>["ore"=>3, "obsidian"=>12]]];
$costs = array();
$cost = array();
$max = array();
$states = array();
$maxGeodes = 0;

function readInput(): void
{
    global $costs;
    $lines = file('input.txt');
    foreach ($lines as $k=>$line) {
        $costs[$k] = array();
        trim($line);
        $line = preg_replace("!.*: !", "", $line);
        $parts = explode(". ", substr($line,0,strlen(trim($line))-1));
        for($i=0; $i<count($parts); $i++) {
            preg_match_all("!Each (.*) robot|(\d+?) ([a-z]*)!", $parts[$i], $matches);
            for ($m=1; $m<count($matches[2]); $m++) {
                $costs[$k][$matches[1][0]][$matches[3][$m]] = $matches[2][$m];
            }
        }
    }
}

function buildBot($bot, $bots, $goods, $time, $maxTime) {
    global $cost, $max;

    // check if we actually need any more of these bots
    if ($bots[$bot] >= $max[$bot]) return false;

    $t = 0;
    foreach ($cost[$bot] as $k => $v) {
        // do we have anyone collecting these supplies yet?
        if ($bots[$k] == 0) return false;
        // do we have enough supplies?
        else if ($goods[$k] < $v) {
            // we don't have the supplies yet, let's see how many minutes until we can build it
            $gr = $v-$goods[$k];
            $t = max($t, intdiv($gr, $bots[$k])+($gr%$bots[$k] == 0?1:2));
        } else {
            // we have the supplies, only takes 1 round to build it
            $t = max($t, 1);
        }
        $goods[$k]-=$v;
    }

    // check if we have enough time to build this robot and use it for at least one round
    if ($time + $t > $maxTime-1) return false;

    // we can build this bot, update the data
    foreach($goods as $k=>$v) {
        $goods[$k]+=$t*$bots[$k];
    }
    $time+=$t;
    $bots[$bot]++;

    return ["bots"=>$bots, "goods"=>$goods, "time"=>$time];
}
function countGeodes($maxTime=24, $bots = ["ore"=>1,"clay"=>0,"obsidian"=>0,"geode"=>0], $goods=["ore"=>0, "clay"=>0, "obsidian"=>0, "geode"=>0], $time=0) {
    global $cost, $states, $maxGeodes;
    $geodes = 0;

    // check if there are enough rounds left to make another geode bot assuming we could make an obs bot every round
    $tl = $maxTime - $time;
    $obsGoods = $goods["obsidian"];
    if ($tl > 2) {
        // need to have enough goods with 2 rounds left in order to build and make use of a new geode bot
        $obsGoods = $goods["obsidian"] + $bots["obsidian"] * ($tl - 2) + ($tl-2) * ($tl - 3) / 2;
    }
    if ($tl == 1 || $cost["geode"]["obsidian"] > $obsGoods) {
        // we don't have enough time left to make another geode bot
        if ($maxGeodes < $goods["geode"]+$bots["geode"]*$tl)
            $maxGeodes = $goods["geode"]+$bots["geode"]*$tl;
        return $goods["geode"]+$bots["geode"]*$tl;
    }
    // check if it's even possible to get higher than our current max if we could build a geode bot every round left
    if ($goods["geode"] + $bots["geode"]*$tl + $tl*($tl-1)/2 <= $maxGeodes) return 0;

    $state = implode(":", $bots).":".implode(":", $goods);
    if (array_key_exists($state, $states)) {
        // we saw this state already, check if it was earlier than now
        if ($states[$state] <= $time) {
            return 0;
        }
    }
    $states[$state] = $time;

    foreach($bots as $bot=>$c) {
        $data = buildBot($bot, $bots, $goods, $time, $maxTime);
        if ($data !== false) {
            $g = countGeodes($maxTime, $data["bots"], $data["goods"], $data["time"]);
            if ($g !== false) {
                $geodes = max($geodes, $g);
            }
        }
    }

    return $geodes;
}

function part1(): void
{
    global $costs, $cost, $max, $states, $maxGeodes;
    $bp = array();
    $total=0;
    foreach ($costs as $b=>$c) {
        $max = array("geode"=>INT_MAX);
        $states = array();
        // determine max usable per minute of each resource
        foreach ($c as $bot) {
            foreach ($bot as $k=>$v) {
                $max[$k] = max($max[$k]??0, $v);
            }
        }

        $cost = $c;
        $maxGeodes = 0;
        $g = countGeodes(24);
        $bp[$b+1] = $g;
        $total+=($b+1)*$g;
    }
    echo "Total = ".$total."\n";
}

function part2(): void
{
    global $costs, $cost, $max, $states, $maxGeodes;
    $bp = array();
    $total=1;
    for ($c=0; $c<3; $c++) {
        $max = array("geode"=>INT_MAX);
        $states = array();
        // determine max usable per minute of each resource
        foreach ($costs[$c] as $bot) {
            foreach ($bot as $k=>$v) {
                $max[$k] = max($max[$k]??0, $v);
            }
        }

        $cost = $costs[$c];
        $maxGeodes = 0;
        $g = countGeodes(32);
        $bp[$c+1] = $g;
        $total*=$g;
    }
    echo "Total = ".$total."\n";
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


