<?php

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