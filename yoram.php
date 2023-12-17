<?php
header('Access-Control-Allow-Origin: *');
require_once 'php/html_page_init.php';

echo $memberId;

//$dt = new DateTime("022-01-30T05:20:51Z", new DateTimeZone('Asia/Amman'));

//echo $dt->format('Y-m-d h:i:s');

$time = strtotime("022-01-30T05:20:51Z".' UTC');
$dateInLocal = date("Y-m-d H:i:s", $time);

echo $dateInLocal;