<?php
############################################
##            LICENSE AFL3.0              ##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

class swam{
	private	$next;
	private $workit;
	public  $printer;
	public 	$temp;
	public  $file  		= "./engine/write.php";	
	public 	$debug_mode = false;

	function __construct($workit){
		$this->workit = $workit;
		$this->temp   = fopen($this->file, 'wr+');
		fwrite($this->temp,"<?php\n");
	}

	private function check_on($row,$i){
		$debug_mode = $this->debug_mode;
		$check 		= $this->workit->get_string_between($row," "," ");
		if($debug_mode){
			echo "Control ON <br>";
			echo "Check Line '".$row."'<br>";
			echo "Check Value '".$check."'<br>";
		}
		if($check ==  "on"){
			$this->workit->line[$i][0]	=  	$this->workit->delete_first_tag($row," on");
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
		$check 		= $this->workit->get_string_between($row," "," ");
		if($debug_mode){
			echo "Control IN <br>";
			echo "Check Line '".$row."'<br>";
			echo "Check Value '".$check."'<br>";
		}
		if($check ==  "in"){
			$this->workit->line[$i][0]	=  $this->workit->delete_first_tag($row," in");
			if($debug_mode)	echo "<b>Control Passed</b><br><hr>";

			return 1;
		}
		else{
			if($debug_mode)	echo "<b>Control Not Passed</b><br><hr>";

			return 0;
		}
	}

	private function check($row,$i){
		if($this->check_on($row,$i))
			return $this->on_read($i);
		else if($this->check_in($row,$i))
			return $this->in_read($i);
		else
			return 1;
	}

	public function read(){
		$lenght 	= $this->workit->lenght;
		$line  		= $this->workit->line;
		$debug_mode = $this->debug_mode;

		for($i = 0; $i < $lenght-1 ; $i++){
			#echo "Checking the line ".($i+1)." Containing ".($line[$i][0])."<br>";
			$line[$i][0] = str_replace("\n", "", $line[$i][0]);
			$this->workit->line[$i][0] = " ".$line[$i][0]." ";
			if($debug_mode)	echo "$i - Setting Array '".$this->workit->line[$i][0]."'<br>";
		}
		if($debug_mode)	echo "<hr>";

		return $this->check($this->workit->line[0][0],0);
	}

	private function on_read($i){
		$lenght 	= $this->workit->lenght;
		$line   	= $this->workit->line;
		$debug_mode = $this->debug_mode;

		//Current line
		$current		=	$this->workit->line[$i][0];
		//Current name of tag
		$spoiler 		=	$this->workit->get_string_between($current," "," ");
		//Open tag
		$this->printer .= "echo '<";
		//Insert name of tag
		$this->printer .=  	$spoiler;
		if($debug_mode)	echo "<b>Opening $spoiler</b><br>";

		//Extract the tag and change previous variable
		$this->workit->line[$i][0] 		=	$this->workit->delete_first_tag($current," ".$spoiler);
		$cur_pos						            =	$this->workit->line[$i][1];	//Save positions
		//Print the element inside line after the tag
		$this->detail_read($i);
		//Close Tag
		$this->printer	.=	">';";
		//Update $this->next value
		$this->next 	=	$i+1;
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

				$this->printer	.=	"echo '</$spoiler>';";
				fwrite($this->temp,$this->printer);
				$this->printer  =	"";
				return 0;
			}
		}
		else{
			if($debug_mode)	echo "<b>Last Line - Closing $spoiler</b><br><hr>";

			$this->printer	.=	"echo '</$spoiler>';";
			fwrite($this->temp, $this->printer);
			$this->printer  =	"";
			return 1;
		}
	}

	private function in_read($i){
		$lenght 	= $this->workit->lenght;
		$line   	= $this->workit->line;
		$debug_mode = $this->debug_mode;

		//Current line
		$current		=	$this->workit->line[$i][0];
		//Current name of tag
		$spoiler 		=	$this->workit->get_string_between($current," "," ");
		//Open tag
		switch ($spoiler) {
			case 'php':
				break;
			
			default:
				$this->printer .= "echo '<";
				//Insert name of tag
				$this->printer .=  	$spoiler;	
				break;
		}

		if($debug_mode)	echo "<b>Opening $spoiler</b><br>";

		//Extract the tag and change previous variable
		$this->workit->line[$i][0] 		=	$this->workit->delete_first_tag($current," ".$spoiler);
		$cur_pos						=	$this->workit->line[$i][1];	//Save position
		//Print the element inside line after the tag
		$this->detail_read($i);
		//Close Tag
		if ($spoiler == 'php') ;
		else $this->printer	.=	">";
		//Update $this->next value
		$this->next 	=	$i+1;
		if($debug_mode){
			echo "Next Value: ".$this->next;
			echo "<br>Index Value: $i<br>";
		}
		//Check next line
		if (($this->next) < ($lenght-1)){

			while (((($line[$this->next][1]) - $cur_pos) == 1 ) && ($this->check($line[$this->next][0],$this->next) == 1)) {
				if($debug_mode)	echo "Next line is a content of <b>$spoiler</b><br><hr>";

				$this->content_read($this->next);
				if($debug_mode)	echo "<b>Reading content on line $this->next</b><br>";

				$this->next++;
			}
			if ((($line[$this->next][1]) <= $cur_pos) || ((($line[$this->next][1]) - $cur_pos) > 1 )) {
				if($debug_mode)	echo "Out of <b>$spoiler</b> tag - <b>Closing $spoiler</b><br><hr>";

				if ($spoiler == 'php') ;
				else $this->printer	.=	"</$spoiler>';";
				fwrite($this->temp,$this->printer);
				$this->printer  =	"";
				return 0;
			}
		}
		else{
			if($debug_mode)	echo "<b>Last Line - Closing $spoiler</b><br><hr>";

			if ($spoiler == 'php') ;
			else $this->printer	.=	"</$spoiler>';";
			fwrite($this->temp,$this->printer);
			return 1;
		}
	}

	private function content_read($i){
		$current	= $this->workit->line[$i][0];
		$current 	= trim($current, " ");
		if ($current == '') ;
		else $this->printer  =   $this->printer.$current;
		return 0;
	}
	private function detail_read($i){
		$line   	=   $this->workit->line;
		$current	=   $line[$i][0];
        while($current) {
            $element    = $this->workit->get_string_between($current, " ", " ");
            $current = $this->workit->delete_first_tag($current, " " . $element);
            $sign       = $element[0];
            switch($sign){
                case '$':
                    $this->printer = $this->printer . "'.".$element.".'";
                    break;
                case '#':
                    $element       = substr($element, 1);
                    $this->printer = $this->printer . " id=\"".$element."\"";
                    break;
                case '@':
                    $element       = substr($element, 1);
                    $this->printer = $this->printer . " class=\"".$element."\"";
                    break;
                default:
                    if(strlen($element)>1) {
                        $this->printer = $this->printer . " " . $element;
                    }
                    else{
                        $this->printer = $this->printer .  $element;
                    }
                    break;
            }
        }
		return 0;
	}
}