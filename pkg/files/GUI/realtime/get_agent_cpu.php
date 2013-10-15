<?php
// Documentation:
// http://www.atlart.com/js/3rdparty/FusionWidgets/Index.html

function contains($needle, $haystack) {
	$pos = strpos(" " . $haystack, $needle);

	if($pos === false) {
		// string needle NOT found in haystack
		return false;
	}
	else {
		// string needle found in haystack
		return true;
	}
}

function agentcmd($host, $port, $cmd, $timeout = 55) {
	$errorno = '';
	$errorstr = '';
	$rv = '';

	try {
		$resource = fsockopen($host, $port, $errorno, $errorstr, $timeout);
		//Attempt to establish a connection to agent on port 9998. On error, place the error number into $errorno, and a string response to $errorstr. Timeout after 10 seconds.
		if (!$resource) {
			//fsockopen failed
			echo "No connection established. Error: " . $errorstr . "[" . $errorno . "]\n";
		} else {
			// successfully opened a socket
			fwrite($resource, $cmd);
			//while there is data to read from $resource…
			while (!feof($resource)) {
				//read the data, 2048 bytes at a time and echo it out to stdout
				$rv .= fgets($resource, 2048);
			}
			//no more data to read, close the resource
			fclose($resource);
		}
	} catch (Exception $e) {
		print "Error:";
		var_dump($e->getMessage());
	}
	return $rv;
}


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


$agent_ver = agentcmd($host, $port, "ver");
$output = agentcmd($host, $port, "sadc_cpu");


// Output Format:
// &label=<TIME>&value=<VALUE>
$values = explode(",", $output);
echo '&label=';
echo date("g:i:sa");
echo '&value=';

if ( contains("Windows", $agent_ver) ) {	// Windows; don't add WIO
	echo "{$values[1]}|{$values[2]}";
}
else {
	echo "{$values[1]}|{$values[2]}|{$values[3]}";
}
?>
