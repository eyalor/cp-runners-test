<?php 
$distance="6.03";
$hours="00";
$minutes="34";
$seconds="13";

$time=($hours*3600)+($minutes*60)+$seconds;
$speed=$time/$distance;
$formated=date('i:s', $speed);

echo $formated;
?>