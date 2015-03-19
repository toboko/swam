<?php
include './engine/swam.php';
$string = file_get_contents("string.swa");
SWAM($string,$workit,$debug,$parser);