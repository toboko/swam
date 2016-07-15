<?php
############################################
##						LICENSE AFL3.0							##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################
include("workit.php");
include("parser.php");
//Init the class
$swam	= new SWcompile($debug);
//Class used to compile the project's files
class SWcompile
{
	//Class var
	private $workit;
	private $debug;

	function __construct($debug)
	{
		//Init Class var.
		$this->workit = new workit($debug);
	}
	function parse($input)
	{
		//Let's split the string
		$this->workit->tokenize($input);
		try {
			//Init Parser
			$parser = new swam($this->workit);
			//Writing code
			$parser->read();
			//Output
			return $parser->printer;
		} catch (Exception $e) {
			echo "Error :" . $e;
		}
	}
}
