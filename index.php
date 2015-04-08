<?php
include 'VAST.php';
header('Access-Control-Allow-Origin: *');
//showErrors();

$obj= new VAST();
$obj->gnrFileUrl='http://www.green-red.com/nayon.mp4';
$obj->gnrImpresssionURL='http://www.green-red.com';
$obj->gnrClickThroughURL="https://www.facebook.com/GandRTech";
$obj->generateVAST();
?>
