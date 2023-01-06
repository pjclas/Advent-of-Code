<?php

const INT_MAX = 9223372036854775807;

$spells = [["cost"=>53, "damage"=>4, "armor"=>0, "heal"=>0],
           ["cost"=>73, "damage"=>2, "armor"=>0, "heal"=>2],
           ["cost"=>113, "damage"=>0, "armor"=>0, "heal"=>0, "effect"=>["name"=>"armor", "turns"=>6, "value"=>7]],
           ["cost"=>173, "damage"=>0, "armor"=>0, "heal"=>0, "effect"=>["name"=>"poison", "turns"=>6, "value"=>3]],
           ["cost"=>229, "damage"=>0, "armor"=>0, "heal"=>0, "effect"=>["name"=>"mana", "turns"=>5, "value"=>101]]];
$bossHp = $bossDamage = 0;
$lowestMana = INT_MAX;
function readInput(): void
{
    global $bossHp, $bossDamage;
    $lines = file('input.txt');
    $bossHp = explode(": ", trim($lines[0]))[1];
    $bossDamage = explode(": ", trim($lines[1]))[1];
}
/*
function doBattle($w, $a, $rings)
{
    global $bossHp, $bossDamage, $armor;
    $ra = $rd = 0;
    foreach ($rings as $r) {
        $ra += $r["armor"];
        $rd += $r["damage"];
    }
    $pd = (($w["damage"]+$rd)-$armor>0)?$w["damage"]+$rd-$armor:1;
    $pt = ceil($hp/$pd);
    $bd = ($damage-($a["armor"]+$ra)>0)?$damage-($a["armor"]+$ra):1;
    $bt = ceil(100/$bd);

    return $pt<$bt+1;
}*/

function takeTurns($hp, $bossHp, $mana, $effects = array(), $myspells=array(), $turn=1, $totalMana=0, $win = true)
{
    global $spells, $bossDamage, $lowestMana;

    // check if we want cheapest to win or most expensive to lose
    if ($win) $manaCost = INT_MAX;
    else $manaCost = 0;

    if ($totalMana >= $lowestMana) return false;
   // for ($i=0; $i<$turn; $i++)
   //     echo "--";
   // echo "hp=$hp bosshp=$bossHp mana=$mana turn=$turn, totalMana=$totalMana\n";
    // check if we won
    if ($bossHp<=0) {
        //echo "mana cost is $totalMana\n";
        //print_r($myspells);
        return $totalMana;
    }
    $hp-=1;
    if ($hp<=0) return false;  // we lost

    // handle any active effects
    foreach ($effects as $k=>$e) {
   //     for ($i=0; $i<$turn; $i++)
   //         echo "--";
   //     echo "effect ".$e["name"]." turns left=".$e["turns"]."\n";
        switch($k) {
            case "poison":
                $bossHp-=$e["value"];
                // check if we won
                if ($bossHp<=0) {
          //          echo "mana cost is $totalMana\n";
          //          print_r($myspells);
                    return $totalMana;
                }
                break;
            case "mana":
                $mana+=$e["value"];
                break;
        }
        if ($e["turns"] == 1) unset($effects[$k]);
        else $effects[$k]["turns"]--;
    }

    $castSpell = false;
    foreach ($spells as $k=>$s) {
        $newEffects = $effects;
        if ($mana >= $s["cost"]) {
            if (array_key_exists("effect", $s)) {
                if (!in_array($s["effect"]["name"], $effects)) {
                    $newEffects[$s["effect"]["name"]] = $s["effect"];
                } else continue;  // spell already active
            }
     //       for ($i=0; $i<$turn; $i++)
     //           echo "--";
     //       echo "Casting spell $k\n";
            $newHp = $hp;
            $castSpell = true;
            $newSpells = $myspells;
            $newSpells[] = $k;
            $newHp+=$s["heal"];

            $armor = 0;
            $newBossHp = $bossHp;
            $newMana = $mana;
            // handle any active effects
            foreach ($newEffects as $name=>$e) {
                switch($e["name"]) {
                    case "armor":
                        $armor += $e["value"];
                        break;
                    case "poison":
                        $newBossHp-=$e["value"];
                        // check if we won
                        if ($newBossHp<=0) {
            //                echo "mana cost is ".($totalMana+$s["cost"])."\n";
            //                print_r($myspells);
                            return $totalMana + $s["cost"];
                        }
                        break;
                    case "mana":
                        $newMana+=$e["value"];
                        break;
                }
                if ($e["turns"] == 1) unset($newEffects[$name]);
                else $newEffects[$name]["turns"]--;
            }

            $d = $bossDamage-$armor;
            $newHp-=($d>0)?$d:1;

            $result = takeTurns($newHp, $newBossHp-$s["damage"], $newMana-$s["cost"], $newEffects, $newSpells, $turn+1, $totalMana+$s["cost"], $win);
            if ($result !== false) {
                if ($result < $manaCost) {
                    $manaCost = $result;
                    if ($result < $lowestMana) $lowestMana = $result;
                }
            }
        }
    }
    if (!$castSpell) return false;

    return $manaCost;
}
function part1(): void
{
    global $bossHp;
    $mana = takeTurns(50, $bossHp, 500);
    echo "Part 1: Least mana spent is $mana\n";
}

function part2(): void
{
   // $gold = checkGear(false);
   // echo "Part 2: Most gold is $gold\n";
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
