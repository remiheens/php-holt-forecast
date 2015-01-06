<?php

require_once __DIR__ . '/vendor/autoload.php';

$stats = array();
for($i=1;$i<=50;$i++)
{
    $stats[]=($i*rand(100,150))/10;
}
$lastValue = array_pop($stats);
$holt = new Forecast\Holt($stats, 0.3, 0.3);
$nextValue = $holt->next();

foreach($stats as $s)
{
    echo $s."\n";
}
echo 'next real value : '.$lastValue."\n";
echo 'next value calculated : '.$nextValue;