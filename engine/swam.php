<?php
############################################
##            LICENSE AFL3.0              ##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

include("common.php");

function SWAM($string,$workit,$debug,$parser){

	$workit->tokenize($string);
	//Debugging the code
	if($debug->syntax_checking()){
		//Error code
		exit();
	}
	else{
		//Writing code
		$parser->read();
        //Opening Temp File
		require $parser->file;
		fclose($parser->temp);
		unlink($parser->file);
	}
}