<?php
############################################
##						LICENSE AFL3.0							##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################


include("workit.php");
include("debug.php");
include("parser.php");
//Setttings of your project
$settings = array(
	//Paths
	'uncompiled' => './proj',
	'compiled' => './main',
	//Debug Mode
	'debug' => false
);

$workit	= new workit($settings);
$debug 	= new debug($workit);
