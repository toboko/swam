<?php
class workit{

	//Array containg all the rows
	public 	$line	=	array();
	//Token used to split and recognize the rows
	private	$token 	=	"\t";
	//Lenght of array
	public	$lenght;
	//Debug Mode
	private $debug_mode = false;

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
		$a 		=	1;
		//Initializing Array
		$this->line[0][1] = 0;
		//Function to split using a token
		$tok 	=	strtok($string, $this->token);
		while ($tok) {
			if($debug_mode) echo "<b>token '".$tok."'</b><br>";
			$this->line[$a-1][0] = $tok;
			if($debug_mode) echo $this->line[$a-1][1]." Word = ".$this->line[$a-1][0]." <br>";
			$tok_new = strtok($this->token);
			$parsed	 = $this->get_string_between($string, $tok, $tok_new);
			$string  = $this->delete_first_tag($string,$tok);
			$this->line[$a][1]  = strlen($parsed);
			$tok = $tok_new;
			$a++;
		}
		$this->lenght = count($this->line);
	}
}
