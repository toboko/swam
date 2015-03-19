<?php

include("workit.php");
include("debug.php");
include("parser.php");

$workit	= new workit();
$debug 	= new debug($workit);
$parser = new swam($workit);