<?php

// class to assist in browsing log files from an online php script


class DcLogViewer {
	public $logfiles = array();
	public $baseUrl = '';
	public $lineCount = 100;
	public $currentFilepath = '';



public function set_baseUrl($baseUrl) {
	$this->baseUrl = $baseUrl;
	}

public function set_lineCount($lineCount) {
	$this->lineCount = $lineCount;
	}


public function clearFiles() {
	$this->logfiles = array();
	}


public function addFile($filepath) {
	$this->logfiles[] = array(
		'label' => $filepath,
		'path' => $filepath,
		);
	//echo 'ADDED FILE: ' . $filepath . '<br/>';
	}


public function addFileStringList($str) {
	// add new line separated list of files
	// ATTN: TODO - support wildcard directories, etc.
	$strarray = explode("\n",$str);
	foreach ($strarray as $apath) {
		$apath = trim($apath);
		if (empty($apath)) {
			continue;
			}
		$this->addFile($apath);
		}
}


public function getBaseUrl() {
	return $this->baseUrl;
	}


public function makeUrlLinkForLogFile($path) {
	$urlencode_path = urlencode($path);
	$url = $this->getBaseUrl() . '&filepath=' . $urlencode_path;
	return $url;
}


public function makeFileListAsHtml() {
	$hretv = '<ul>';
	foreach ($this->logfiles as $logfile) {
		$label = htmlentities($logfile['label']);
		$path = $logfile['path'];
		$url = $this->makeUrlLinkForLogFile($path);
		$hretv .= '<li><a href="' . $url . '">' . $label . '</a></li>' . "\n";
		}
	$hretv .= '</ul>' . "\n";
	return $hretv;
	}






public function readFile($filepath) {
	// first verify it's in our list, otherwise refuse
	$foundlogfile = false;
	foreach ($this->logfiles as $logfile) {
		if ($logfile['path']==$filepath) {
			$foundlogfile = $logfile;
			break;
			}
		}
	if (!$foundlogfile) {
		return false;
		}

	// ok we found it now really load it
	$this->currentFilepath = $filepath;

	// true;
	return true;
	}


public function getFileContents() {

	if (empty($this->currentFilepath)) {
		return '';
		}

	$tailcontents = $this->tail($this->currentFilepath, $this->lineCount);
	//echo 'TAILCONTENTS of "' . $this->currentFilepath . '": <pre>' . htmlentities($tailcontents) . '</pre>';
	$lines = explode("\n",$tailcontents);

	$hretv = '';
	$hretv .= '<h2>Contents of ' . htmlentities($this->currentFilepath) . ':</h2>' . "\n";
	$hretv .= '<ol reversed>';
	foreach ($lines as $line) {
		$hretv .= '<li> <span class="dccodeline">' . htmlentities($line) . '</span></li>';
		}
	$hretv .= '</ol>';

	return $hretv;
	}















// from server-logs-viewer.php by pixeline.be, pixeline
public function tail($filename, $lines = 50, $buffer = 4096){
	// Open the file
	if(!is_file($filename)){
		return false;
	}
	$f = fopen($filename, "rb");
	if(!$f){
		echo ' ERROR: COULD NOT OPEN FILE "' . $filename . '".';
		return false;
	}
	
	// Jump to last character
	fseek($f, -1, SEEK_END);

	// Read it and adjust line number if necessary
	// (Otherwise the result would be wrong if file doesn't end with a blank line)
	if(fread($f, 1) != "\n") $lines -= 1;

	// Start reading
	$output = '';
	$chunk = '';

	// While we would like more
	while(ftell($f) > 0 && $lines >= 0)
	{
		// Figure out how far back we should jump
		$seek = min(ftell($f), $buffer);

		// Do the jump (backwards, relative to where we are)
		fseek($f, -$seek, SEEK_CUR);

		// Read a chunk and prepend it to our output
		$output = ($chunk = fread($f, $seek)).$output;

		// Jump back to where we started reading
		fseek($f, -mb_strlen($chunk, '8bit'), SEEK_CUR);

		// Decrease our line counter
		$lines -= substr_count($chunk, "\n");
	}

	// While we have too many lines
	// (Because of buffer size we might have read too many)
	while($lines++ < 0)
	{
		// Find first newline and remove all text before that
		$output = substr($output, strpos($output, "\n") + 1);
	}

	// Close file and return
	fclose($f);
	return $output;
}




}
