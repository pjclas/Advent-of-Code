<?php

// pull out numbers in a line of text into 2d array
//  preg_match_all('!\d+!', $line, $data);
$lines = file('input.txt');

$in = "vzbxxzaa";
$pass = $in;

while (!checkValidPassword($pass)) {
    $pass++;
    echo $pass."\n";
}

function checkValidPassword($pass) {
    // can't contain i, o, l
    if (str_contains("i", $pass) || str_contains("o", $pass) || str_contains("l", $pass))
        return false;

    $pairs = array();
    $chars = str_split($pass);
    $p1 = "1";
    $p2 = "2";
    $straight = false;
    foreach ($chars as $c) {
        // must contain 3 letter straight
        if (ord($c) - ord($p1) == 1 && ord($p1) - ord($p2) == 1)
            $straight = true;

        // must contain two different pairs
        if ($c == $p1)
            $pairs[] = $c;

        $p2 = $p1;
        $p1 = $c;
    }
    if (count(array_unique($pairs)) < 2 || !$straight)
        return false;
    else
        return true;
}

?>
