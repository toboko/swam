<?php
############################################
##						LICENSE AFL3.0							##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

class swam{
	private $next = 0;
	private $workit;
	public $printer;
	public $debug_mode = null;
	function __construct($workit){
		$this->workit	= $workit;
		$this->debug_mode = $workit->debug_mode;
	}
	//Function to start the parse action
	function start(){
		while (($this->next) < ($this->workit->lenght-1)) {
			if($this->check($this->workit->line[$this->next][0],$this->next)) ;
		}
	}
	//Function to Initializing the array
	public function read(){
		$lenght 	= $this->workit->lenght;
		$line			= $this->workit->line;
		$debug_mode = $this->debug_mode;
		//Cleaning and filling the array
		for($i = 0; $i < $lenght-1 ; $i++) {
			$line[$i][0] = str_replace("\n", "", $line[$i][0]);
			$this->workit->line[$i][0] = " ".$line[$i][0]." ";
			if($debug_mode) echo "$i - Setting Array '".$this->workit->line[$i][0]."'<br>";
		}
		if($debug_mode) echo "<hr>";
		$this->start();
	}
	private function check($row,$i){
		if($this->check_on($row,$i))
		return $this->on_read($i);
		else if($this->check_in($row,$i))
		return $this->in_read($i);
		else
		return 999;
	}
	private function check_on($row,$i){
		$debug_mode = $this->debug_mode;
		$check 		= $this->workit->get_string_between($row," "," ");
		if($debug_mode){
			echo "Control ON <br>";
			echo "Check Line '".$row."'<br>";
			echo "Check Value '".$check."'<br>";
		}
		if($check ==	"on"){
			$this->workit->line[$i][0]	=	$this->workit->delete_first_tag($row," on");
			if($debug_mode)	echo "<b>Control Passed</b><br><hr>";
			return 1;
		}
		else{
			if($debug_mode)	echo "<b>Control Not Passed</b><br><hr>";
			return 0;
		}
	}
	private function check_in($row,$i){
		$debug_mode = $this->debug_mode;
		$check = $this->workit->get_string_between($row," "," ");
		if($debug_mode){
			echo "Control IN <br>";
			echo "Check Line '".$row."'<br>";
			echo "Check Value '".$check."'<br>";
		}
		if($check ==	"in"){
			$this->workit->line[$i][0]	=	$this->workit->delete_first_tag($row," in");
			if($debug_mode)	echo "<b>Control Passed</b><br><hr>";
			return 1;
		}
		else{
			if($debug_mode)	echo "<b>Control Not Passed</b><br><hr>";
			return 0;
		}
	}
	private function on_read($i){
		$lenght 	= $this->workit->lenght;
		$line	 		= $this->workit->line;
		$debug_mode = $this->debug_mode;
		//Current line
		$current		=	$this->workit->line[$i][0];
		//Current name of tag
		$spoiler 	=	$this->workit->get_string_between($current," "," ");
		//Open tag
		$this->printer .= "<";
		//Insert name of tag
		$this->printer .=	$spoiler;
		if($debug_mode)	echo "<b>Opening $spoiler</b><br>";
		//Extract the tag and change previous variable
		$this->workit->line[$i][0] = $this->workit->delete_first_tag($current," ".$spoiler);
		//Save positions
		$cur_pos = $this->workit->line[$i][1];
		//Print the element inside line after the tag
		$general_line = $this->workit_to_string($i);
		$this->auto_read($general_line);
		//Close Tag
		$this->printer .= ">";
		//Update $this->next value
		$this->next = $i+1;
		if($debug_mode){
			echo "Next Value: ".$this->next;
			echo "<br>Index Value: $i<br>";
		}
		//Check next line
		if (($this->next) < ($lenght-1)){
			while (($line[$this->next][1]) > $cur_pos) {
				if($debug_mode)	echo "Major Values of <b>$spoiler</b><br><hr>";
				if($this->check($this->workit->line[$this->next][0],$this->next)) ;
				else if($debug_mode)	echo "<b>Reading on line $this->next</b><br>";
			}
			if (($line[$this->next][1]) <= $cur_pos) {
				if($debug_mode)	echo "Equal or Min Values - <b>Closing $spoiler</b><br><hr>";
				$this->printer	.=	"</$spoiler>";
				return 0;
			}
		}
	}
	private function in_read($i){
		$lenght = $this->workit->lenght;
		$line = $this->workit->line;
		$debug_mode = $this->debug_mode;
		//Current line
		$current		=	$this->workit->line[$i][0];
		//Current name of tag
		$spoiler 		=	$this->workit->get_string_between($current," "," ");
		//Open tag
		$this->printer .= "<";
		//Insert name of tag
		$this->printer .=		$spoiler;

		if($debug_mode) echo "<b>Opening $spoiler</b><br>";
		//Extract the tag and change previous variable
		$this->workit->line[$i][0] = $this->workit->delete_first_tag($current," ".$spoiler);
		//Position saved
		$cur_pos = $this->workit->line[$i][1];
		//Print the element inside line after the tag
		$general_line = $this->workit_to_string($i);
		$this->auto_read($general_line);
		//Close Tag
		$this->printer .= ">";
		//Update $this->next value
		$this->next = $i+1;
		if($debug_mode){
			echo "Next Value: ".$this->next;
			echo "<br>Index Value: $i<br>";
		}
		//Check next line
		if (($this->next) < ($lenght-1)){
			while (((($line[$this->next][1]) - $cur_pos) == 1 )) {
				if($debug_mode)	echo "Next line is a content of <b>$spoiler</b><br><hr>";
				//Reading the content inside the tag in
				$this->content_read($this->next);
				if($debug_mode)	echo "<b>Reading content on line $this->next</b><br>";
				$this->next++;
			}
			if ((($line[$this->next][1]) <= $cur_pos) || ((($line[$this->next][1]) - $cur_pos) > 1 )) {
				if($debug_mode)	echo "Out of <b>$spoiler</b> tag - <b>Closing $spoiler</b><br><hr>";
				$this->printer	.=	"</$spoiler>";
				return 1;
			}
		}
	}
	//This function read the content
	//Is used for in tag
	private function content_read($i){
		//$current is the line passed
		$current	= $this->workit->line[$i][0];
		//removing " " after and before $current
		$current 	= trim($current, " ");
		//if $current is empty don't print anything
		if ($current == '') ;
		//else print $current and its content
		else $this->special_str($current);
		return 0;
	}
	private function auto_read($string) {
		$element = strtok($string, " ");
		while($element !== false) {
			$this->fast_attributes($element);
			$element = strtok(" ");
		}
		return 1;
	}
	private function workit_to_string($row) {
		$line		=	$this->workit->line;
		$string	=	$line[$row][0];
		return $string;
	}
	//This function read the details inside a tag
	//Is used for on tag
	private function fast_attributes($string){
		$sign = $string{0};
		switch($sign) {
		case '$':
			$this->printer = $this->printer . "'.".$string.".'";
			break;
		case '#':
			$string = substr($string, 1);
			$this->printer = $this->printer . " id=\"".$string."\"";
			break;
		case '@':
			$string = substr($string, 1);
			$this->printer = $this->printer . " class=\"".$string."\"";
			break;
		default:
			//This status is checked to optimize the white space useless
			if (strlen($string) > 1)	$this->printer = $this->printer . " " . $string;
			else $this->printer = $this->printer . $string;
			break;
		}
		return 1;
	}
	//This function find special tag inside a content
	private function special_str($string) {
		//Taking string trigger
		$tok 	=	strtok($string, " ");
		//Checking every words inside a lide
		while ($tok !== false) {
			//Selecting the first char to know if is a special char
			$spec = $tok{0};
			//If it is a special char the next chars are the tag to recognize the element
			$tag	=	substr($tok, 1 , strlen($tok) - 1);
			//Let's check if there's a special char
			switch ($spec) {
				case '|':
					//The tag is something in html
					$this->printer = $this->printer."<".$tag;
					$tok = strtok(" ");
					while ($tok{0} != "[") {
						$this->fast_attributes($tok);
						$tok = strtok(" ");
					}
					//If there's only one word open and close in one step
					if ($tok{0} == "[" && $tok{strlen($tok) - 1} == "]" ) {
						$this->printer = $this->printer.">".substr($tok, 1, strlen($tok) - 2);
					}
					//If there are more than one words concat them
					else {
						$this->printer = $this->printer.">".substr($tok, 1 , strlen($tok) - 1)." ";
						$tok = strtok(" ");
						while ($tok{strlen($tok) - 1} != "]") {
							$this->fast_attributes($tok);
							$tok = strtok(" ");
						}
						$this->printer = $this->printer.substr($tok, 0 , strlen($tok) - 1);
					}
					$this->printer	=	 $this->printer." </".$tag.">";
					break;
				default:
					$this->printer	=	 $this->printer.$tok." ";
					break;
			}
			$tok = strtok(" ");
		}
		$this->printer = substr($this->printer, 0 , strlen($this->printer));
		return 0;
	}
}
