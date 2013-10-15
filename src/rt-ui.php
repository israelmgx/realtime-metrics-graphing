<?
/////////////////////////////////////////////////////////
// To troubleshoot, set $debug = 1
// 

$debug = 0;

/////////////////////////////////////////////////////////
// Documentation:
// http://www.atlart.com/js/3rdparty/FusionWidgets/Index.html


// function that returns the name/value pair in an HTTP GET string format
function getNameValueStr($name, $value) {
	return urlencode($name) . '=' . urlencode($value) . '&';
}


// agent hostname
if (isset($_GET["hostname"])) {
	$host = $_GET["hostname"];
} else {
	$host = "localhost";
}
// agent port
if (isset($_GET["port"])) {
	$port = $_GET["port"];
} else {
	$port = "9998";
}

// generate the "GET" string for the XML description page
$getstr = "";
$getstr .= getNameValueStr("t", "Total CPU Usage");
$getstr .= getNameValueStr("hostname", $host);
$getstr .= getNameValueStr("port", $port);
$getstr .= getNameValueStr("xn", "Time");
$getstr .= getNameValueStr("yn", '%');
$getstr .= getNameValueStr("sn", "CPU Total Usage %");
$getstr .= getNameValueStr("ri", "1");
$getstr .= getNameValueStr("ymax", "100");
$getstr .= getNameValueStr("u", "/realtime/rt-out.php");
$url = "/realtime/rt-xml-desc.php" . '?' . urlencode($getstr);
?>

<script language="JavaScript" src="/realtime/FusionWidgets/FusionCharts.js">
</script>
<div id="CPU" align="center">
You shouldn't see this
</div>
<script type="text/javascript">
var myChart1 = new FusionCharts("/realtime/FusionWidgets/RealTimeArea.swf", "rtLine", "850", "400", "<? echo $debug; ?>", "0");
myChart1.setDataURL("<? echo $url; ?>");
myChart1.render("CPU");
</script>



<?
// second graph
// generate the "GET" string for the XML description page
$getstr = "";
$getstr .= getNameValueStr("t", "IIS - Total Method Requests / sec (WMI)");
$getstr .= getNameValueStr("hostname", $host);
$getstr .= getNameValueStr("xn", "Time");
$getstr .= getNameValueStr("yn", 'Requests/sec');
$getstr .= getNameValueStr("sn", "IIS Method Requests");
$getstr .= getNameValueStr("ri", "2");
//$getstr .= getNameValueStr("ymax", "100");
$getstr .= getNameValueStr("u", "/realtime/rt-wmi.php");
$getstr .= getNameValueStr("vbs", "method_requests.vbs");	// vbs file to execute
if ($debug) {
	$getstr .= getNameValueStr("debugMode", "1");	// debug mode for the chart
}
$url = "/realtime/rt-xml-desc.php" . '?' . urlencode($getstr);

?>
<div id="WMI1" align="center">
You shouldn't see this
</div>
<script type="text/javascript">
var myChart2 = new FusionCharts("/realtime/FusionWidgets/RealTimeArea.swf", "rtLine", "850", "400", "<? echo $debug; ?>", "0");
myChart2.setDataURL("<? echo $url; ?>");
myChart2.render("WMI1");
</script>
