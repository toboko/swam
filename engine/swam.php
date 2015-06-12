<?php
############################################
##            LICENSE AFL3.0              ##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

include("common.php");

class compiled{
	
	private $laodir = './main/';
	private $prodir = './proj/';
	
	private $found = false;
	
	function __construct($workit, $debug) {
		$lfiles = scandir($this->laodir);
		$pfiles = scandir($this->prodir);
		
		for ($x = 2 ; $x < count($pfiles); $x++) {
			
			$in = substr($pfiles[$x], 0, -3);
			$output = $this->laodir.$in."php";
			
			if(count($lfiles > 2)) {
				for ($y = 2; $y < count($lfiles); $y++) {
					
					$ou = substr($lfiles[$y], 0, -3);
					$found = false;
					
					if ($ou == $in) {
						$found = true;
						
						if (date('U',filemtime($this->prodir.$pfiles[$x])) > date('U',filemtime($this->laodir.$lfiles[$y]))) {
							$this->action($workit, $debug, $this->prodir.$pfiles[$x], $this->laodir.$lfiles[$y]);
						}
						break;
					}
				}
			}
			if (!$found) {
				$this->action($workit, $debug, $this->prodir.$pfiles[$x], $output);
			}
		}
	}
	
	function action($workit, $debug, $input, $output) {
	
		$code = file_get_contents($input);
	
		$workit->tokenize($code);
		//Debugging the code
		if($debug->syntax_checking()){
			//Error code
			exit();
		}
		else{
			$parser = new swam($workit, $output);
			//Writing code
			$parser->read();
		}
	}
}

$SWAM_COMP	= new compiled($workit, $debug);

