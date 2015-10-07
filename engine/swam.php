<?php
############################################
##						LICENSE AFL3.0							##
## Copyright (c) 2015-2016 Nicola Bombaci ##
############################################

include("common.php");
//Class used to compile the project's files
class compile{
	//Files project compiled - Main Path
	private $laodir = null;
	//Files project - Project Path
	private $prodir = null;
	//Compiled folder array
	private $lfiles;
	//Uncompiled folder array
	private $pfiles;
	//Class var
	private $workit;
	private $debug;

	function __construct($workit, $debug, $settings) {
		//Init Class var.
		$this->workit = $workit;
		$this->debug = $debug;
		//Init Paths Var.
		$this->laodir = $settings['compiled'];
		$this->prodir = $settings['uncompiled'];
		//$lfiles contains all file's name inside the $laodir
		$this->lfiles	=	$this->listFolderFiles($this->laodir);
		//$pfiles contains all file's name inside the $prodir
		$this->pfiles	=	$this->listFolderFiles($this->prodir);
		//Starts to scannig the project files
		$this->updateFolderFiles($this->pfiles);
	}

	function listFolderFiles($dir) {
		$ffs = scandir($dir);
		$list = array($dir);
		foreach ($ffs as $ff) {
			if ($ff != '.' && $ff != '..') {
				if (strlen($ff) >= 5) {
					if (substr($ff, -4) == '.php' || substr($ff, -4) == '.swa') {
							$list[] = $ff;
					}
				}
				if(is_dir($dir.'/'.$ff)) {
					$list[] = $this->listFolderFiles($dir.'/'.$ff);
				}
			}
		}
		return $list;
	}

	function foundFolderFiles ($dir ,$file) {
		$folder = $dir[0];
		$limiter = 0;
		$found = null;
		foreach ($dir as $element) {
			if ($limiter == 0) {
				$limiter ++;
				continue;
			}
			if (is_array($element)) {
				$found = $this->foundFolderFiles($element, $file);
				if( $found != null) return $found;
			}
			else if(substr($element, 0, -3) == $file) {
				return $folder."/".$element;
			}
		}
		return null;
	}

	function updateFolderFiles($dir) {
		$folder = $dir[0];
		$limiter = 0;
		foreach ($dir as $element) {
			if ($limiter == 0) {
				$limiter ++;
				continue;
			}
			else if (is_array($element)) {
				$this->updateFolderFiles($element);
			}
			else {
				$in = substr($element, 0, -3);
				$file_found = $this->foundFolderFiles($this->lfiles, $in);
				if ($file_found != null) {
					echo "<b>File found</b> ".$file_found."<br>";
					if (date('U',filemtime($folder."/".$element)) > date('U',filemtime($file_found))) {
						$this->action($this->workit, $this->debug, $folder."/".$element, $file_found);
						echo "<b>File</b> ".$file_found." <b>UPDATED</b><br>";
					}
				}
				else {
					$pieces = explode("/", $folder);
					if(count($pieces) > 2 ) {
						unset($pieces[0]);
						unset($pieces[1]);
						$mkdir = implode("/", $pieces);
						$mkdir = $this->laodir."/".$mkdir;
					}
					else {
						$mkdir = $this->laodir;
					}
					echo "<hr><b>File</b> ".$mkdir."/".$in."php <b>Not Found</b><br>";
					if (!is_dir($mkdir)) {
						mkdir($mkdir, 0700, true);
						echo "<b>Folder</b> $mkdir <b>Created</b><br>";
					}
					$this->action($this->workit, $this->debug, $folder."/".$element, $mkdir."/".$in."php");
					echo "<b>File created</b><hr>";
				}
			}
		}
	}

	function action($workit, $debug, $input, $output) {
		//Extract the file's project
		$code = file_get_contents($input);
		//Let's split the string
		$workit->tokenize($code);
		//Debugging the code
		echo "<b>$input</b> DEBUGGING <br>";
		if($debug->syntax_checking()){
			echo "<b>$input</b> ERROR<hr>";
			return 1;
		}
		else{
			//Removing old version
			if (file_exists($output)) unlink($output);
			//Init Parser
			$parser = new swam($workit, $output);
			//Writing code
			$parser->read();
			echo "<b>$input</b> COMPILED <br>";
			return 0;
		}
	}
}
//Init the class
$SWAM_COMP	= new compile($workit, $debug, $settings);
