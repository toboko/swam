<?php
############################################
##            LICENSE AFL3.0              ##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################


include("workit.php");
include("debug.php");
include("parser.php");

$debug_mode = false;

$workit	= new workit($debug_mode);
$debug 	= new debug($workit);
