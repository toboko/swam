<?php
############################################
##            LICENSE AFL3.0              ##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

include("common.php");
//Class used to compile the project's files
class compiled{
	//Files project compiled - Main Path
	private $laodir = './main/';
	//Files project - Project Path
	private $prodir = './proj/';
	
	private $found = false;

	function __construct($workit, $debug) {
		//$lfiles contains all file's name inside the $laodir
		$lfiles = scandir($this->laodir);
		//$pfiles contains all file's name inside the $prodir
		$pfiles = scandir($this->prodir);
		//Starts to scannig the project files
		for ($x = 2 ; $x < count($pfiles); $x++) {
			//Checking the execption
			if ($pfiles[$x] == ".htaccess" | ".htpasswd") echo "File <b>".$pfiles[$x]."</b> IGNORED <hr>";
			else {
				//Removing the extensions of a file like .php or .java
				$in = substr($pfiles[$x], 0, -3);
				//Building the output file
				$output = $this->laodir.$in."php";
				//If the main dir contains files, means that there's file allready compiled
				//We must scan and try to compile the file updated
				if(count($lfiles > 2)) {
					for ($y = 2; $y < count($lfiles); $y++) {
						//Removing the extensions
						$ou = substr($lfiles[$y], 0, -3);
						//Init the var $found if we compiled something in the passt
						//This val alert the presence of a file
						$found = false;
						//If the file's project is already compiled
						if ($ou == $in) {
							//File allready compiled founded
							$found = true;
							//If the the file's project is newest let's update the last compiled version
							if (date('U',filemtime($this->prodir.$pfiles[$x])) > date('U',filemtime($this->laodir.$lfiles[$y])))
								$this->action($workit, $debug, $this->prodir.$pfiles[$x], $this->laodir.$lfiles[$y]);
							}
							break;
						}
					}
				}
				//If we've never compiled the file's project let's make
				if (!$found) {
					$this->action($workit, $debug, $this->prodir.$pfiles[$x], $output);
				}
			}
		}

	function action($workit, $debug, $input, $output) {
		//Extract the file's project
		$code = file_get_contents($input);
		//Let's split the string
		$workit->tokenize($code);
		//Debugging the code
		echo "DEBUGGING <b>$input</b><br>";
		if($debug->syntax_checking()){
			echo "ERROR in <b>$input</b><hr>";
			return 1;
		}
		else{
			//Removing old version
			if (file_exists($output)) unlink($output);
			//Init Parser
			$parser = new swam($workit, $output);
			//Writing code
			$parser->read();
			echo "COMPILED <b>$input</b><hr>";
			return 0;
		}
	}
}
//Init the class
$SWAM_COMP	= new compiled($workit, $debug);
