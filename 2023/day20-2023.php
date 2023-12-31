<?php

function readInput() : void {
    global $modules, $conjMods;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $line = explode(" -> ", trim($line));
        if ($line[0] == "broadcaster") $modules["broadcaster"]["mods"] = explode(", ", $line[1]);
        else {
            $mod = ["type"=>$line[0][0], "mods"=>explode(", ", $line[1])];
            $name = substr($line[0],1);
            if ($mod["type"] == '%') {
                $mod["state"] = 0;
            } else {
                $conjMods[] = $name;
            }
            $modules[$name] = $mod;
        }
    }
    // add the initial memory for conjunction modules
    foreach ($conjMods as $name) {
        foreach ($modules as $key=>$mod) {
            if (in_array($name, $mod["mods"])) $modules[$name]["mem"][$key] = 0;
        }
    }
}

function pushButton(&$modules, $count=0) : mixed {
    $pulses = [1,0];
    $q = array();
    foreach ($modules["broadcaster"]["mods"] as $mod) {
        $q[] = [0,$mod,null];
    }
    while ($q) {
        list($p, $m, $src) = array_shift($q);
        $pulses[$p]++;
        if ($m == "rx") {
            if (!$p) return true;
            else continue;
        }
        if ($modules[$m]["type"] == '%' && $p==0) {
            $modules[$m]["state"]++;
            $modules[$m]["state"]%=2;
            $p = $modules[$m]["state"];
        } elseif ($modules[$m]["type"] == '&') {
            $modules[$m]["mem"][$src] = $p;
            $p = (array_sum($modules[$m]["mem"]) == count($modules[$m]["mem"]))?0:1;
            if ($p == 1 && !isset($modules[$m]["count"])) $modules[$m]["count"] = $count;
        } else continue;
        foreach ($modules[$m]["mods"] as $mod) {
            $q[] = [$p, $mod, $m];
        }
    }
    return $pulses;
}

function gcf($a, $b)
{
    if ($b == 0)
        return $a;
    return gcf($b, $a % $b);
}

function findlcm($arr)
{
    $ans = $arr[0];
    for ($i = 1; $i < count($arr); $i++)
        $ans = ((($arr[$i] * $ans)) /
            (gcf($arr[$i], $ans)));

    return $ans;
}

function part1() {
    global $modules;
    $mods = $modules;
    $high = $low = 0;
    for ($c=0; $c<1000; $c++)  {
        $pulses = pushButton($mods);
        $low += $pulses[0];
        $high += $pulses[1];
    }

    print "Part 1: The product of pulses is ".($low*$high)."\n";
}

function part2() {
    global $modules, $conjMods;
    $mods = $modules;
    $count = 0;
    $lastMod = "";
    // find module that outputs to "rx"
    foreach ($conjMods as $m) {
        if (in_array("rx", ($mods[$m]["mods"]))) {
            $lastMod = $m;
            break;
        }
    }

    do {
        $count++;
        $values = array();
        pushButton($mods, $count);
        foreach (array_keys($mods[$lastMod]["mem"]) as $m) {
            if (isset($mods[$m]["count"])) $values[] = $mods[$m]["count"];
        }
    } while (count($values) < 4);

    print "Part 2: The least number of button pushes to turn on the machine is ".findlcm($values)."\n";
}

readInput();
part1();
part2();
