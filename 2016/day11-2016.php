<?php

$floors = array();
function readInput(): void
{
    global $floors;
    $lines = file('input.txt');
    foreach ($lines as $f=>$line) {
        $floors[$f] = ["generators"=>[], "microchips"=>[]];
        if (!str_contains($line, "nothing relevant")) {
            $stuff = explode(",", preg_replace(["!,? and a !", "!.*contains a !", "! a !", "!\.!", "!-compatible!", "! and!"], [",", "", "", "", "", ""], trim($line)));
            foreach ($stuff as $s) {
                $d = explode(" ", $s);
                $floors[$f][$d[1]."s"][$d[0]]=1;
            }
        }
    }
}

function checkConfig($floors)
{
    foreach ($floors as $f) {
        foreach ($f["microchips"] as $m=>$d) {
            if (!array_key_exists($m, $f["generators"]) && count($f["generators"]) != 0) return false;
        }
    }
    return true;
}
function flatten($elevator, $floors) {
    $str = "$elevator:";
    foreach ($floors as $f=>$data) {
        $str.="$f:g:".count($data["generators"])."-m:".count($data["microchips"]);
    }
    return $str;
}

function findMinMoves($floors)
{
    $q = [["moves"=>0, "f"=>0, "floors"=>$floors, "parent"=>[]]];
    $vis[flatten(0, $floors)] = 1;
    while (count($q) != 0) {
        $v = array_shift($q);
        if (count($v["floors"][0]["generators"]) == 0 && count($v["floors"][0]["microchips"]) == 0 &&
            count($v["floors"][1]["generators"]) == 0 && count($v["floors"][1]["microchips"]) == 0 &&
            count($v["floors"][2]["generators"]) == 0 && count($v["floors"][2]["microchips"]) == 0) break;
        // first try to move up a floor
        $moves = [-1,1];
        foreach ($moves as $move) {
            if ($v["f"]+$move<count($floors) && ($v["f"]+$move>=0)) {
                // try all generators
                foreach (array_merge($v["floors"][$v["f"]]["generators"], [0=>0]) as $g1=>$d1) {
                    foreach ($v["floors"][$v["f"]]["generators"] as $g2=>$d2) {
                        if ($g1 == $g2) continue;
                        $nextConfig = $v["floors"];
                        if ($g1 != 0) {
                            unset($nextConfig[$v["f"]]["generators"][$g1]);
                            $nextConfig[$v["f"] + $move]["generators"][$g1] = 1;
                        }
                        unset($nextConfig[$v["f"]]["generators"][$g2]);
                        $nextConfig[$v["f"] + $move]["generators"][$g2] = 1;
                        if (checkConfig($nextConfig) !== false) {
                            $flat = flatten($v["f"] + $move, $nextConfig);
                            if (!array_key_exists($flat, $vis)) {
                                $q[] = ["moves" => $v["moves"] + 1, "f" => $v["f"] + $move, "floors" => $nextConfig, "parent" => $v];
                                $vis[$flat] = 1;
                            }
                        }
                    }
                }
                // try all microchips
                $chips = array_merge($v["floors"][$v["f"]]["microchips"], [0=>0]);
                foreach ($chips as $m1=>$d1) {
                    foreach ($chips as $m2=>$d2) {
                        if ($m1 == $m2) continue;
                        $nextConfig = $v["floors"];
                        if ($m1 != 0) {
                            unset($nextConfig[$v["f"]]["microchips"][$m1]);
                            $nextConfig[$v["f"] + $move]["microchips"][$m1] = 1;
                        }
                        if ($m2 != 0) {
                            unset($nextConfig[$v["f"]]["microchips"][$m2]);
                            $nextConfig[$v["f"] + $move]["microchips"][$m2] = 1;
                        } else if (array_key_exists($m1, $v["floors"][$v["f"]]["generators"])) {
                            unset($nextConfig[$v["f"]]["generators"][$m1]);
                            $nextConfig[$v["f"] + $move]["generators"][$m1] = 1;
                        } else continue;
                        if (checkConfig($nextConfig) !== false) {
                            $flat = flatten($v["f"] + $move, $nextConfig);
                            if (!array_key_exists($flat, $vis)) {
                                $q[] = ["moves" => $v["moves"] + 1, "f" => $v["f"] + $move, "floors" => $nextConfig, "parent" => $v];
                                $vis[$flat] = 1;
                            }
                        }
                    }
                }
            }
        }
    }

    return $v["moves"];
}
function part1(): void
{
    global $floors;

    echo "Part 1: Number of moves = ".findMinMoves($floors)."\n";
}

function part2(): void
{
    global $floors;
    $floors[0]["generators"]["elerium"] = 1;
    $floors[0]["generators"]["dilithium"] = 1;
    $floors[0]["microchips"]["elerium"] = 1;
    $floors[0]["microchips"]["dilithium"] = 1;

    echo "Part 2: Number of moves = ".findMinMoves($floors)."\n";
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
