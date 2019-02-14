<?php
$time = file_get_contents('./time.txt');
$newTime = $time + 4;
//echo $newTime;

$filed = "time.txt";
$rez = $newTime;
file_put_contents($filed, $rez);






?>