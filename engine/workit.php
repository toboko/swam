<?php
############################################
##            LICENSE AFL3.0              ##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

class workit{
	//Array containg all the rows
	public 	$line	=	array();
	//Token used to split and recognize the rows
	private	$token 	=	"\r\n";
	//Lenght of array
	public	$lenght;
	//Debug Mode
	public $debug_mode = null;

	function __construct($debug)
	{
		$this->debug_mode = $debug;
	}
	public function get_string_between($string, $start, $end)
	{
		$ini = strpos($string,$start);
		$ini += strlen($start);
		$len = strpos($string,$end,$ini) - $ini;
		return substr($string,$ini,$len);
	}
	public function delete_first_tag($string, $start)
	{
		$ini = strpos($string,$start);
		$ini += strlen($start);
		return substr($string, $ini);
	}
	public function tokenize($string)
	{
		$debug_mode = $this->debug_mode;
		//Used to refer the line
		$a = 0;
		//Function to split the string using a token
		$tok = strtok($string, $this->token);
		while ($tok)
		{
			if($debug_mode) echo "<b>token '".$tok."'</b><br>";
			$count = null;
			preg_replace('/(\\/\\/+)/i', '', $tok, -1, $count);
			//Check if it is a comment line
			if ($count < 1)
			{
				//Getting the number of /t contained inside a line
				//Saving the content of the string modified inside the array
				$this->line[$a][0] = preg_replace ('/[\\t]/', '', $tok, -1, $count);
				//Saving the result of the previous function inside the array
				$this->line[$a][1] = $count;
				//Saving the words' counter
				$this->line[$a][2] = count(str_word_count($this->line[$a][0], 1, "0..9"));
				if($debug_mode) echo $this->line[$a][1]." Word = '".$this->line[$a][0]."' <br>";
				//Run the array
				$a++;
			}
			//Removing from the original string the piece analyzed
			$string = $this->delete_first_tag($string,$tok);
			//Calculating the new token line
			$tok = strtok($this->token);
		}
		//Updating lenght of the array
		$this->lenght = count($this->line);
		if($debug_mode) echo "<hr>Lenght(Array) = ".$this->lenght."<hr>";
	}
}
