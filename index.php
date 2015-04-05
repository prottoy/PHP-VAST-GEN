<?php
include 'VAST.php';

showErrors();

$obj= new VAST();
$obj->generateVAST();

function showErrors(){
    ini_set('display_errors',1);
    ini_set('display_startup_errors',1);
    error_reporting(-1);    
}
?>
