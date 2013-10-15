<?
/////////////////////////////////////////////////////////
// To troubleshoot, set $debug = 1
// 

$debug = 0;

/////////////////////////////////////////////////////////
// Documentation:
// http://www.atlart.com/js/3rdparty/FusionWidgets/Index.html


// urldecode the GET string (query_string)
$arr = explode('&', urldecode($_SERVER['QUERY_STRING']));

if ($debug) {
	$f = fopen("rt_xml_desc_debug.txt", 'w');
	fwrite($f, "Encoded Query String:\n");
	fwrite($f, $_SERVER['QUERY_STRING'] . "\n");
	fwrite($f, "Decoded Query String:\n");
	fwrite($f, urldecode($_SERVER['QUERY_STRING']) . "\n\n");
	
	fwrite($f, "VARIABLES\n");
}

// go through all of the variables
for ($i = 0; $i < count($arr); $i++) {
	if (strlen($arr[$i]) > 0) {
		// split the line into the key/value pairs
		$line = split('=', $arr[$i]);
		$line[0] = urldecode($line[0]);
		$line[1] = urldecode($line[1]);

		if ($debug) {
			fwrite($f, $line[0] . ": " . $line[1] . "\n");
			//echo "Key: " . $line[0] . "\n";
			//echo "Value: " . $line[1] . "\n";
		}

		if (isset($line[1]) && strlen($line[1]) > 0) {
			if ($line[0] == "hostname") {
				$host = $line[1];
			}
			if ($line[0] == "port") {
				$port = $line[1];
			}
			if ($line[0] == "t") {
				$title = $line[1];
			}
			if ($line[0] == "xn") {
				$xname = $line[1];
			}
			if ($line[0] == "yn") {
				$yname = $line[1];
			}
			if ($line[0] == "ymax") {
				$ymax = $line[1];
			}
			if ($line[0] == "sn") {
				$seriesname = $line[1];
			}
			if ($line[0] == "ri") {
				$refreshinterval = $line[1];
			}
			if ($line[0] == "u") {
				$url = $line[1];
			}
			if ($line[0] == "vbs") {
				$vbs = $line[1];
			}
		}
	}
}

if ($debug) { fclose($f); }

// set defaults if no arguments were set
// agent hostname
if (!isset($host)) {
	$host = "localhost";
}
// agent port
if (!isset($port)) {
	$port = "9998";
}
// chart title
if (!isset($title)) {
	$title = "Real Time Monitor";
}
// series name
if (!isset($seriesname)) {
	$seriesname = "No_Series_Name";
}
// refresh interval (default: 1)
if (!isset($refreshinterval)) {
	$refreshinterval = 1;
}



// function that returns the name/value pair in an HTTP GET string format
function getNameValueStr($name, $value) {
	return urlencode($name) . '=' . urlencode($value) . '&';
}

// create URL string for the realtime data stream
$url .= "?";	// add the "?" to start the GET string
$url .= getNameValueStr("hostname", $host);
$url .= getNameValueStr("port", $port);
if (isset($vbs)) { $url .= getNameValueStr("vbs", $vbs); }

?>
<chart caption='<? echo $title; ?>' 
 subCaption='<? echo $host; ?>' 
 refreshInterval='<? echo $refreshinterval; ?>' 
 dataStreamURL='<? echo $url; ?>' 
 numberPrefix='' 
 showRealTimeValue='0' 
 realTimeValuePadding='1'
 xAxisNamePadding='50' 
 labelDisplay='Rotate' 
 slantLabels='1' 
<?
 if (isset($xname)) { echo " xAxisName='" . $xname . "'"; }
 if (isset($yname)) { echo " yAxisName='" . $yname . "'"; }
 if (isset($ymax))  { echo " yAxisMinValue='1' yAxisMaxValue='" . $ymax . "'"; }
?>
>
	<categories></categories>
	<dataset seriesName='<? echo $seriesname; ?>' showValues='1'></dataset>
	<styles>
		<definition>
			<style type='font' name='captionFont' size='14' />
		</definition>
		<application>
			<apply toObject='Caption' styles='captionFont' />
			<apply toObject='Realtimevalue' styles='captionFont' />
		</application>
	</styles>
</chart>
