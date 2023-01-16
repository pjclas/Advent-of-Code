<?php

class Elf {
    public $num;
    public $next;
    public $prev;
    public function __construct($num)
    {
        $this->num = $num;
        $this->next = null;
        $this->prev = null;
    }
}
class Elves {
    public $head;
    public $middle;
    public $count;
    public function __construct()
    {
        $this->head = null;
        $this->middle = null;
        $this->count = 0;
    }
    public function addElf($num)
    {
        $elf = new Elf($num);
        if ($this->head == null) {
            $elf->prev = $elf->next = $elf;
            $this->head = $elf;
            $this->middle = $elf;
            $this->count = 1;
        } else {
            $tail = $this->head->prev;
            $elf->next = $this->head;
            $this->head->prev = $elf;
            $elf->prev = $tail;
            $tail->next = $elf;
            $this->count++;
            if ($this->count % 2 == 0) {
                $this->middle = $this->middle->next;
            }
        }
    }

    public function removeMiddleElf()
    {
        $this->middle->prev->next = $this->middle->next;
        $this->middle->next->prev = $this->middle->prev;
        if ($this->count%2 == 1) {
            $this->middle = $this->middle->next->next;
        } else {
            $this->middle = $this->middle->next;
        }
        $this->head = $this->head->next;
        $this->count--;
    }
}
function part1(): void
{
    $elves = array_fill(0,3005290, 1);
    do {
        $keys = array_keys($elves);
        for ($e=0; $e<count($keys); $e+=2) {
            unset($elves[$keys[($e+1)%count($keys)]]);
        }
    } while (count($elves) > 1);

    echo "Part 1: Elf with all the presents is ".(key($elves)+1)."\n";
}

function part2(): void
{
    // used a doubly lined list to keep track of the middle easier
    // could've also done this a bit simpler by dividing the input into 2 arrays and removing elements from end of first or beginning of second depending on even/odd count :shrug:
    $elves = new Elves();
    for ($i=0; $i<3005290; $i++) {
        $elves->addElf($i+1);
    }
    do {
        $elves->removeMiddleElf();
    } while ($elves->count > 1);

    echo "Part 2: Elf with all the presents is ".$elves->middle->num."\n";
}

$start = microtime(true);
part1();
$start2 = microtime(true);
$time_elapsed_secs =  $start2 - $start;
echo "Time: $time_elapsed_secs\n";
part2();
$time_elapsed_secs = microtime(true) - $start2;
echo "Time: $time_elapsed_secs\n";

// This is a more clever solution based on the winner pattern, hard to come up with
/*    $w = 1;
    for ($i = 1; $i < $num; $i++) {
        $w = ($w % $i) + 1;
        if ($w > intdiv($i + 1, 2)) {
            $w++;
        }
    }
    echo "$num - $w\n";
*/