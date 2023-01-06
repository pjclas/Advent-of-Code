<?php

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);
$lines = file('input.txt');

$monkeys = array();
//$dirs = str_split($in);
//$input = "3113322113";

// Recursive function to compute gcd (euclidian method)
/*function gcd ($a, $b) {
    return $b ? gcd($b, $a % $b) : $a;
}
// Then reduce any list of integer
echo array_reduce(array(42, 56, 28), 'gcd'); // === 14

return;*/

function gcd($a, $b)
{
    if ($b == 0)
        return $a;
    return gcd($b, $a % $b);
}
 
// Returns LCM of array elements
function findlcm($arr, $n)
{
     
    // Initialize result
    $ans = $arr[0];
 
    // ans contains LCM of
    // arr[0], ..arr[i]
    // after i'th iteration,
    for ($i = 1; $i < $n; $i++)
        $ans = ((($arr[$i] * $ans)) /
                (gcd($arr[$i], $ans)));
 
    return $ans;
}
$factors = array();
//$seq = str_split($input);
foreach ($lines as $line) {
    $line = trim($line);
    if (str_contains($line, "Monkey")) {
        if (is_array($monkey))
            $monkeys[] = $monkey;
        $monkey = array("count"=>0);
    } else if (str_contains($line, "Starting")) {
        preg_match_all('!\d+!', $line, $data);
        $monkey['items'] = $data[0];
    } else if (str_contains($line, "Operation")) {
        $op = substr($line, 11);
        $monkey['oper'] = $op;
    } else if (str_contains($line, "Test")) {
        $monkey['test'] = intval(array_pop(explode(" ", $line)));
        $factors[] = $monkey['test'];
    } else if (str_contains($line, "true")) {
        $monkey['true'] = intval(array_pop(explode(" ", $line)));
    } else if (str_contains($line, "false")) {
        $monkey['false'] = intval(array_pop(explode(" ", $line)));
    }
}
$monkeys[] = $monkey;

$lcm = findlcm($factors, count($factors));
for ($r=0; $r<10000; $r++) {
    echo "Round ".($r+1)."\n";
    //print_r($monkeys);
    for ($k=0; $k<count($monkeys); $k++) {
        $m = $monkeys[$k];
        echo "Monkey $k\n";
        foreach ($m['items'] as $item) {
            echo "Item $item\n";
            $monkeys[$k]["count"]++;
            // apply operation on item being inspected
/*            if (str_contains($m['oper'], "*")) {
                $mult = explode(" ", str_replace("old",$item,$m['oper']));
                $p = intval($mult[4]);
                $w = bcmul($mult[2], $p);
            } else {
                $mult = explode(" ", str_replace("old",$item,$m['oper']));
                $w = bcadd($mult[2], $mult[4]);
//                    $w =eval("$".str_replace("old",$item,$m['oper'])."; return \$new;")%$sub;
            }*/
            $w =eval("$".str_replace("old",$item,$m['oper'])."; return \$new;");
           // echo "w = $w \n";
//            if (bcmod($w, $m['test']) == 0) {
            if ($w % $m['test'] == 0) {
                $arr = array();
                foreach($monkeys as $a){
                    if (bcmod($w, $a['test']) == 0) {
                        $arr[] = $a['test'];
                    }
                }
                echo "$w divisible by:\n";
                print_r($arr);
                //$w = findlcm($arr, count($arr));
                echo "throwing $w to monkey ".$m['true']."\n";

                $monkeys[$m['true']]['items'][] = $w%$lcm;
            } else {
                echo "throwing $w to monkey ".$m['false']."\n";
                $monkeys[$m['false']]['items'][] = $w%$lcm;
            }
        }
        // monkey threw all their items
        $monkeys[$k]["items"] = array();
    }
}

//print_r($monkeys);
//echo "subtract $sub\n";

$counts = array();
foreach ($monkeys as $k=>$m) {
    echo "Monkey $k count=".$m["count"]."\n";
    $counts[] = $m["count"];
}
rsort($counts);
print_r($counts);
echo $counts[0]*$counts[1]."\n";
?>
