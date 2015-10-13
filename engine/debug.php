 <?php
############################################
##						LICENSE AFL3.0							##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

class debug{
	private $workit;
	private $pos_out = 999;

	function __construct($workit){
		$this->workit = $workit;
	}

	function syntax_checking(){
		$lenght = $this->workit->lenght;
		$line	 = $this->workit->line;

		for($i = 0; $i < $lenght-1 ; $i++){
			$this->checking_body($i);
			//Count the last position into the array about "body"
			if (($line[$i+1][1]-$line[$i][1]) > 1) {
				echo "<b>Error</b> in your <b>syntax</b> check on line <b>".($i+2)."</b><br>";
				return 1;
			}
			if (($i > $this->pos_out) && ($line[$i][1] <= 1)) {
				echo "<b>Error</b> out of <b>body</b> check on line <b>".($i+1)."</b><br>";
				return 1;
			}
		}
	}
	private function checking_body($i){
		$line	 	=	 $this->workit->line;
		$current	=	 $line[$i][0];
		$current	=	 " ".$current;
		while($current) {
			$element = $this->workit->get_string_between($current, " ", " ");
			$current = $this->workit->delete_first_tag($current, " " . $element);
			if($element == "body"){
					$this->pos_out = $i;
			}
		}
	}
}
