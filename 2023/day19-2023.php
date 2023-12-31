<?php

function readInput() {
    global $wfs, $parts;
    $lines = file('input.txt');
    $partSection = false;
    foreach ($lines as $line) {
        $line = trim($line);
        if (empty($line)) {
            $partSection = true;
            continue;
        }
        if ($partSection) {
            $part = array();
            $p = explode(",", trim($line, "{}"));
            foreach ($p as $trait) {
                $t = explode("=", $trait);
                $part[$t[0]] = $t[1];
            }
            $parts[] = $part;
        } else {
            preg_match('/(.*){/', $line, $wf);
            $wf = $wf[1];
            $rules = explode(",", preg_replace('/.*{(.*)}/', '$1', $line));
            $wfs[$wf] = $rules;
        }
    }
}

function processWorkflow($part, $workflow) {
    list($x,$m,$a,$s) = array_values($part);
    foreach ($workflow as $cond) {
        if (str_contains($cond, ":")) {
            $cond = explode(":", $cond);
            if (eval("return ($".$cond[0].");")) {
                return $cond[1];
            }
        } else return $cond;
    }
    return 'R';
}

function processPart($part, $wfs) {
    $wf = "in";
    do {
        $wf = processWorkflow($part, $wfs[$wf]);
    } while ($wf != 'A' && $wf != 'R');
    return ($wf == 'A'?array_sum($part):0);
}

function getAccepted($wfs, $wf="in", $ratings=["x"=>[1,4000],"m"=>[1,4000],"a"=>[1,4000],"s"=>[1,4000]]) {
    $total = 0;
    if ($wf == 'R') return 0;
    if ($wf == 'A') {
        $t = 1;
        foreach ($ratings as $r) {
            $t*=$r[1]-$r[0]+1;
        }
        return $t;
    }
    foreach ($wfs[$wf] as $cond) {
        $nextR = $ratings;
        if (str_contains($cond, ":")) {
            $cond = explode(":", $cond);
            if ($cond[0][1] == '>') {
                $nextR[$cond[0][0]][0] = intval(substr($cond[0],2))+1;
                $ratings[$cond[0][0]][1] = intval(substr($cond[0],2));
            } else {
                $nextR[$cond[0][0]][1] = intval(substr($cond[0],2))-1;
                $ratings[$cond[0][0]][0] = intval(substr($cond[0],2));
            }
            $next_wf = $cond[1];
        } else $next_wf = $cond;
        $total+=getAccepted($wfs, $next_wf, $nextR);
    }
    return $total;
}

function part1() {
    global $parts, $wfs;
    $total = 0;
    foreach ($parts as $part) {
        $total+=processPart($part, $wfs);
    }
    print "Part 1: The sum of part number ratings is $total\n";
}

function part2() {
    global $wfs;
    $total = getAccepted($wfs);
    print "Part 2: The total combinations of ratings is $total \n";
}

readInput();
part1();
part2();
