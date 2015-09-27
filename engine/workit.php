<?php
############################################
##            LICENSE AFL3.0              ##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

class workit{

	//Array containg all the rows
	public 	$line	=	array();
	//Token used to split and recognize the rows
	private	$token 	=	"\t";
	//Lenght of array
	public	$lenght;
	//Debug Mode
	public $debug_mode = null;

	function __construct($debug){
		$this->debug_mode = $debug;
	}

	public function get_string_between($string, $start, $end){
		$ini = strpos($string,$start);
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}

	public function delete_first_tag($string, $start){
		$ini = strpos($string,$start);
		$ini += strlen($start);
		return substr($string, $ini);
	}

	public function tokenize($string){
		$debug_mode = $this->debug_mode;
		//Used to refer the line
		$a 	=	1;
		//Initializing Array
		$this->line[0][1] = 0;
		//Function to split using a token
		$tok 	=	strtok($string, $this->token);
		while ($tok) {
			if($debug_mode) echo "<b>token '".$tok."'</b><br>";

			$this->line[$a-1][0] = $tok;
			
			if($debug_mode) echo $this->line[$a-1][1]." Word = ".$this->line[$a-1][0]." <br>";

			//Calculating the new token line
			$tok_new = strtok($this->token);
			//Getting the number of /t in a line
			$parsed	 = $this->get_string_between($string, $tok, $tok_new);
			//Cleaning the $string
			$string  = $this->delete_first_tag($string,$tok);
			//Calculating the number of /t in a line
			$this->line[$a][1]  = strlen($parsed);
			//Pass throw the new token
			$tok = $tok_new;
			//Run the array
			$a++;
		}
		if($debug_mode) echo "<hr>";
		$this->lenght = count($this->line);
	}
}
