<?php

function readInput() {
    global $hail;
    $lines = file('input.txt');
    foreach ($lines as $line) {
        $h = explode(" @ ", trim($line));
        $hail[] = ["pos"=>explode(", ", $h[0]), "vel"=>explode(", ", $h[1])];
    }
}

function gcf($a, $b)
{
    if ($b == 0)
        return $a;
    return gcf($b, $a % $b);
}
function gcf_array($array, $a = 0)
{
    $b = array_pop($array);
    return ($b === null) ?
        (int)$a :
        gcf_array($array, gcf($a, $b));
}

function sortHail($a,$b) {
    list($xv1,$yv1,$zv1) = $a["vel"];
    list($xv2,$yv2,$zv2) = $b["vel"];
    $vel1 = abs($xv1)+abs($yv1)+abs($zv1);
    $vel2 = abs($xv2)+abs($yv2)+abs($zv2);

    if ($vel1==$vel2) return 0;
    else return ($vel1<$vel2?1:-1);
}

function getIntersectPoint($l1p1, $l1p2, $l2p1, $l2p2, $checkZ=true) {
    list($x1,$y1,$z1) = $l1p1;
    list($x2,$y2,$z2) = $l1p2;
    list($x3,$y3,$z3) = $l2p1;
    list($x4,$y4,$z4) = $l2p2;

    // given 2 lines with 2 points on each, we can determine if they intersect by the following
    // formulas
    $sn = ($x3-$x1)*($y2-$y1) - ($y3-$y1)*($x2-$x1);
    $sd = ($y4-$y3)*($x2-$x1) - ($x4-$x3)*($y2-$y1);
    if ($sd == 0) {
        // Lines don't intersect since x,y values are parallel
        return false;
    }
    $s = $sn/$sd;

    $eq1x = ($x4 - $x3) * $s + $x3;
    $eq1y = ($y4 - $y3) * $s + $y3;
    $eq1z = ($z4 - $z3) * $s + $z3;
    if ($checkZ) {
        // if we get here then these lines would intersect in 2d space, let's test the Z space
        $t = (($x4-$x3)*$s+$x3-$x1)/($x2-$x1);
        $eq2z = ($z2 - $z1) * $t + $z1;
        if ($eq1z != $eq2z) return false;
    }

    return [$eq1x, $eq1y, $eq1z];
}
function part1() {
    global $hail;
    $total = 0;
    $min=7;
    $max=27;
    $numHail = count($hail);
    for ($i=0; $i<$numHail-1; $i++) {
        list($x1,$y1,$z1) = $hail[$i]["pos"];
        list($xv1,$yv1,$zv1) = $hail[$i]["vel"];
        for ($j=$i+1; $j<$numHail; $j++) {
            list($x2,$y2,$z2) = $hail[$j]["pos"];
            list($xv2,$yv2,$zv2) = $hail[$j]["vel"];
            $c = getIntersectPoint($hail[$i]["pos"], [$x1+$xv1,$y1+$yv1,$z1+$zv1], $hail[$j]["pos"], [$x2+$xv2,$y2+$yv2,$z2+$zv2], false);
            if ($c!==false &&
                $c[0]>=$min && $c[0]<=$max &&
                $c[1]>=$min && $c[1]<=$max &&
                (($xv1>0 && $c[0]>=$x1) || ($xv1<0 && $c[0]<=$x1)) &&
                (($xv2>0 && $c[0]>=$x2) || ($xv2<0 && $c[0]<=$x2))) {
                $total++;
            }
        }
    }
    print "Part 1: The number of lines in 2d that intersect are $total \n";
}

function part2() {
    global $hail;

    // sort hail so fastest stones are at beginning of list
   // usort($hail,"sortHail");
/*
    $h1 = $hail[6];//array_shift($hail);
    $h2 = $hail[179];//array_shift($hail);
    $done = false;
    $t=1;
    list($x1,$y1,$z1) = $h1["pos"];
    list($xv1,$yv1,$zv1) = $h1["vel"];
    list($x2,$y2,$z2) = $h2["pos"];
    list($xv2,$yv2,$zv2) = $h2["vel"];
    $loops=0;
    do {
        $done = true;
        // create plane for point at time $t on line h1 to any two points on line h2
        $x1+=$xv1;
        $y1+=$yv1;
        $z1+=$zv1;
        $xv3 = $x2 - $x1;
        $yv3 = $y2 - $y1;
        $zv3 = $z2 - $z1;
        // take cross product of v2 and v3 to get normal vector
        $n = [$yv2 * $zv3 - $yv3 * $zv2, -($xv2 * $zv3 - $xv3 * $zv2), $xv2 * $yv3 - $xv3 * $yv2];

        // find where next stone intercepts plane and make a line to it
        // then check if all other stones intersect with that line
        list($x4,$y4,$z4) = $hail[218]["pos"];
        list($xv4,$yv4,$zv4) = $hail[218]["vel"];
        // check where this hail stone intersects the plane of our line
        $x5=$x4+$xv4;
        $y5=$y4+$yv4;
        $z5=$z4+$zv4;
        // Substituting formulas for x,y,z into our plan equation will give us
        // the coordinates where a line intersects the plane.
        // If the denominator is 0 then our line is parallel to our plane and doesn't intersect.
        $tn = -$n[0]*($x4-$x1)-$n[1]*($y4-$y1)-$n[2]*($z4-$z1);
        $td = $n[0]*($x5-$x4)+$n[1]*($y5-$y4)+$n[2]*($z5-$z4);
        if ($td != 0) {
            $t = $tn/$td;
            $ix = $t*($x5-$x4)+$x4;
            $iy = $t*($y5-$y4)+$y4;
            $iz = $t*($z5-$z4)+$z4;
//            print "Intersection at $ix,$iy,$iz\n";

            // now loop through other stones to see if they all intersect this new line
            $count=0;
            foreach ($hail as $h) {
                list($hx,$hy,$hz) = $h["pos"];
                list($hxv,$hyv,$hzv) = $h["vel"];
                $c = getIntersectPoint([$x1,$y1,$z1],[$ix,$iy,$iz],$h["pos"],[$hx+$hxv,$hy+$hyv,$hz+$hzv]);
                if ($c === false) {
                    $done = false;
                    break;
                }
                else {
                    $count++;
                    list($x,$y,$z) = $c;
//                    print "Intersection at $x,$y,$z\n";
                }
            }
        //    print "Intersected with $count lines\n";
        }
        $loops++;
        if ($loops % 1000000 == 0) print "$x1,$y1,$z1\n";
    } while (!$done);*/

    for($i=0; $i<4; $i++) {
        list($x1,$y1,$z1) = $hail[$i]["pos"];
        list($xv1,$yv1,$zv1) = $hail[$i]["vel"];
        print "eqs.append(($x1-x)*(yv-($yv1))-($y1-y)*(xv-($xv1)))\n";
        print "eqs.append(($y1-y)*(zv-($zv1))-($z1-z)*(yv-($yv1)))\n";
    }
  //  print "Part 1: The total winnings for all hands is $total \n";
}
function checkParallel() {
    global $hail;
    $div = 1;
    for ($i=0; $i<count($hail)-2; $i++) {
        list($x1,$y1,$z1) = $hail[$i]["pos"];
        list($xv1,$yv1,$zv1) = $hail[$i]["vel"];
        for ($j=$i+1; $j<count($hail); $j++) {
            list($x2, $y2, $z2) = $hail[$j]["pos"];
            list($xv2, $yv2, $zv2) = $hail[$j]["vel"];
            if (abs($xv1) > abs($xv2)) {
                $m = $xv1 / $xv2;
                if ($yv1 / $yv2 == $m || $zv1 / $zv2 == $m || $yv1 / $yv2 == $zv1 / $zv2) {
                    $x1/=$div;
                    $y1/=$div;
                    $z1/=$div;
                    $x2/=$div;
                    $y2/=$div;
                    $z2/=$div;
                    print "$i: ($x1,$y1,$z1) - ($xv1,$yv1,$zv1) || $j: ($x2,$y2,$z2) - ($xv2,$yv2,$zv2)\n";
                }
            } else {
                $m = $xv2 / $xv1;
                if ($yv2 / $yv1 == $m || $zv2 / $zv1 == $m || $yv2 / $yv1 == $zv2 / $zv1) {
                    $x1/=$div;
                    $y1/=$div;
                    $z1/=$div;
                    $x2/=$div;
                    $y2/=$div;
                    $z2/=$div;
                    print "$i: ($x1,$y1,$z1) - ($xv1,$yv1,$zv1) || $j: ($x2,$y2,$z2) - ($xv2,$yv2,$zv2)\n";
                }
            }
        }
    }
}
readInput();
part1();
//checkParallel();
part2();
