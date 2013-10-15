<?php
/////////////////////////////////////////////////////////
// To troubleshoot, set $debug = 1
// 

$debug = 0;

/////////////////////////////////////////////////////////
// Documentation:
// http://www.atlart.com/js/3rdparty/FusionWidgets/Index.html

if (isset($_GET["hostname"])) {
	$host = $_GET["hostname"];
} else {
	$host = "localhost";
}
if (isset($_GET["port"])) {
	$port = $_GET["port"];
} else {
	$port = 9998;
}
if (isset($_GET["vbs"])) {
	$vbsfile = $_GET["vbs"];
} else {
	$vbsfile = "test.vbs";
}

if (file_exists(escapeshellcmd($vbsfile))) {

	// attempt to execute the WMI file: "cscript //nologo vbsfile.vbs hostname"
	// Output Format:
	// &label=<TIME>&value=<VALUE>
	// remove possibly bad shell characters
	$cmd = 'cscript.exe //nologo ' . escapeshellcmd($vbsfile) . ' ' . $host;
	if ($debug) {
		echo "Debug: Command sent to shell:\n";
		echo $cmd . "\n";
	}
	exec($cmd, $strout);
	echo trim($strout[0]);	// execute vbs on the shell and return all output to screen (no filter)
}
else {
	echo "Error: File '" . escapeshellcmd($vbsfile) . "' does not exist.\n";
	echo "\nCurrent directory and files:\n";
	echo passthru("cd");
	echo "\n";
	echo passthru("dir");
}

/*
$values = explode(",", $in);
echo '&label=';
echo date("g:i:sa");
echo '&value=';
echo ((float)$values[1] + (float)$values[2] + (float)$values[3]);
*/

?>
