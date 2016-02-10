<?php
include './engine/swam-min.php';
//Getting contents in strings
$code = file_get_contents("./proj/string.swa");
//Parsing code
echo $swam->parse($code);
