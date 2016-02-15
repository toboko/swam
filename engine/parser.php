<?php
############################################
##						LICENSE AFL3.0							##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

class swam
{
	private $next = 0;
	private $tag = "on";
	private $workit;
	public $printer;
	public $debug_mode = null;
	function __construct($workit)
	{
		$this->workit	= $workit;
		$this->debug_mode = $workit->debug_mode;
	}
	//Function to start the parse action
	function start()
	{
		while (($this->next) < ($this->workit->lenght - 1))
			$this->check($this->workit->line[$this->next][0],$this->next);
	}
	//Function to Initializing the array
	public function read()
	{
		$lenght = $this->workit->lenght;
		$debug_mode = $this->debug_mode;
		//Cleaning and filling the array
		for($i = 0; $i < $lenght; $i++)
		{
			$this->workit->line[$i][0] = " ".str_replace("\n", "", $this->workit->line[$i][0])." ";
			if($debug_mode) echo "$i - Setting Array '".$this->workit->line[$i][0]."'<br>";
		}
		if($debug_mode) echo "<hr>";
		$this->start();
	}
	private function check($row,$i)
	{
		if($this->check_tag($row,$i))
		return $this->tag_read($i);
		else
		{
			//Reading the content inside the tag in
			$this->auto_read($row);
			if($this->debug_mode) echo "<b>Reading content on line $this->next</b><hr>";
			$this->next++;
		}
	}
	private function check_tag($row,$i)
	{
		$debug_mode = $this->debug_mode;
		$check = $this->workit->get_string_between($row," "," ");
		if($debug_mode)
		{
			echo "Control ON <br>";
			echo "Check Line '".$row."'<br>";
			echo "Check Value '".$check."'<br>";
		}
		if($check == $this->tag)
		{
			$this->workit->line[$i][0] = $this->workit->delete_first_tag($row," on");
			if($debug_mode) echo "<b>Control Passed</b><br><hr>";
			return true;
		}
		else{
			if($debug_mode)	echo "<b>Control Not Passed</b><br><hr>";
			return false;
		}
	}
	private function tag_read($i)
	{
		$debug_mode = $this->debug_mode;
		//Current line
		$current = $this->workit->line[$i][0];
		//Current name of tag
		$spoiler = $this->workit->get_string_between($current," "," ");
		//Open tag
		$this->printer .= "<";
		//Insert name of tag
		$this->printer .= $spoiler;
		if($debug_mode) echo "<b>Opening $spoiler</b><br>";
		//Extract the tag and change previous variable
		$this->workit->line[$i][0] = $this->workit->delete_first_tag($current," ".$spoiler);
		//Save positions
		$cur_pos = $this->workit->line[$i][1];
		//Print the element inside line after the tag
		$this->auto_read($this->workit->line[$i][0]);
		//Close Tag
		$this->printer .= ">";
		//Update $this->next value
		$this->next++;
		if($debug_mode)
		{
			echo "Next Value: ".$this->next;
			echo "<br>Index Value: $i<br>";
		}
		//Check next line
		while (($this->workit->line[$this->next][1] > $cur_pos) && ($this->next < $this->workit->lenght))
		{
			if($debug_mode) echo "Major Values of <b>$spoiler</b><br><hr>";
			$this->check($this->workit->line[$this->next][0],$this->next);
			if (!isset($this->workit->line[$this->next][0])) break;
		}
		if($debug_mode) echo "Equal or Min Position - <b>Closing $spoiler</b><br><hr>";
		$this->printer .= "</$spoiler>";
	}
	//Function reading the content of tag
	private function auto_read($string)
	{
		$element = strtok($string, " ");
		while($element !== false)
		{
			$this->fast_attributes($element);
			$element = strtok(" ");
		}
	}
	//This function reading the details inside a tag
	private function fast_attributes($string)
	{
		$sign = $string{0};
		switch($sign)
		{
		case '#':
			$string = substr($string, 1);
			$this->printer = $this->printer . " id=\"".$string."\"";
			break;
		case '.':
			$string = substr($string, 1);
			$this->printer = $this->printer . " class=\"".$string."\"";
			break;
		default:
			//This status is checked to optimize the white space useless
			if (strlen($string) > 1) $this->printer = $this->printer . " " . $string;
			else $this->printer = $this->printer . $string;
			break;
		}
	}
}
