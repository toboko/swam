<?php
############################################
##            LICENSE AFL3.0              ##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

class workit{
	//Array containg all the rows
	public 	$line	=	array();
	//Array measuring amount of indentation on rows
	public  $indent =   array();
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
		//Stores the indent length
		$tab = 0;
		//Function to split the string using a token
		$tok = strtok($string, $this->token);
		while ($tok)
		{
			if($debug_mode) echo "<b>token '".$tok."'</b><br> ";
			$count = null;
			preg_replace('/^[\\s\\t]*(\\/\\/)+/i', '', $tok, -1, $count);
			//Check if it is a comment line
			if ($count < 1)
			{
				//Getting the number of /t contained inside a line
				//Saving the content of the string modified inside the array
				$this->indent[$a] = preg_split('/^[\\s\\t]+/', $tok, -1, PREG_SPLIT_OFFSET_CAPTURE);
				if ($tab < 1)
				{
					//initialize indentation spacing amount
					$tab = $this->indent[$a][1][1] | 0;
					$this->line[$a][1] = $this->indent[$a][1][1] < 1 ? 0 : 1;
				}
				else
				{
					// set amount of indentation
					$this->line[$a][1] = $this->indent[$a][1][1] / $tab;
				}
				$this->line[$a][0] = $this->line[$a][1] < 1 ? $this->indent[$a][0][0] : $this->indent[$a][1][0];
				$this->line[$a][2] = count(str_word_count($this->line[$a][0], 1, "0..9"));
				if($debug_mode) echo str_repeat("&nbsp;", 5)."indented ".$this->line[$a][1]." x ".$tab.", text is <i>".$this->line[$a][0]."</i><br>";
				//Run the array
				$a++;
			}
			else {
				if($debug_mode) echo str_repeat("&nbsp;", 5)."<i>comment line</i><br>";
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
