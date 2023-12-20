<?php


function part1() {
    $lines = file('input.txt');
    foreach ($lines as $line) {
        preg_match_all('!\d!', $line, $matches); 
        $values[] = $matches[0][0].end($matches[0]);
    }
    print "Part 1: Sum of calibration values = ".array_sum($values)."\n"; 
}

function part2() {
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $line = str_replace(["oneight","threeight","fiveight","nineight","twone","sevenine","eightwo","eighthree",
                             "one","two","three","four","five","six","seven","eight","nine"],
                    [18,38,58,98,21,79,82,83,1,2,3,4,5,6,7,8,9],
                    $line);
        preg_match_all('!\d!', $line, $matches); 
        $values[] = $matches[0][0].end($matches[0]);
    }
    print "Part 2: Sum of calibration values = ".array_sum($values)."\n"; 
}

part1();
part2();

?>
