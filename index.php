<?php
include 'VAST.php';
//showErrors();

$obj= new VAST();
$obj->GNRfileUrl='http://www.green-red.com/nayon.mp4';
$obj->GNRImpresssionURL='http://www.green-red.com';
$obj->GNRClickThroughURL="https://www.facebook.com/GandRTech";
$obj->generateVAST();

function showErrors(){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);    
}
?>
