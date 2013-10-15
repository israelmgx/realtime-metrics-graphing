<?php
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

//Attempt to establish a connection to agent on port 9998. On error, place the error number into $errorno, and a string response to $errorstr. Timeout after 10 seconds.
$resource = fsockopen($host, $port, $errorno, $errorstr, 1);
if (!$resource) {
	//fsockopen failed
	echo "No connection established. Error: " . $errorstr . "[" . $errorno . "]<br />\n";
} else {
	$cmd = "sadc_cpu";
	$in = "";
	fwrite($resource, $cmd);
	//fsockopen succeeded
	//while there is data to read from $resource…
	$lines = 0;
	while (!feof($resource)) {
		//read the data, 128 bytes at a time and echo it out
		$in .= fgets($resource, 128);
		$lines++;
	}
	//no more data to read, close the resource
	fclose($resource);
}


// Output Format:
// &label=<TIME>&value=<VALUE>
$values = explode(",", $in);
echo '&label=';
echo date("g:i:sa");
echo '&value=';
echo ((float)$values[1] + (float)$values[2] + (float)$values[3]);
?>
