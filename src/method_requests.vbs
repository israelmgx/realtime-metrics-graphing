On Error Resume Next

Const wbemFlagReturnImmediately = &h10
Const wbemFlagForwardOnly = &h20

strComputer  = WScript.Arguments.Item(0)
Set objSWbemLocator = CreateObject("WbemScripting.SWbemLocator")

' NO USER AUTH
'Set objWMIService = GetObject("winmgmts:\\" & strComputer & "\root\CIMV2")
'Set colItems = objWMIService.ExecQuery("SELECT * FROM Win32_PerfRawData_W3SVC_WebService", "WQL", _
'                                          wbemFlagReturnImmediately + wbemFlagForwardOnly)

' WITH USER AUTH
Set objSWbemServices = objSWbemLocator.ConnectServer (strComputer, "root\cimv2", "uptimedemo\administrator", "uptime")
objSWbemServices.Security_.ImpersonationLevel = 3
Set colItems = objSWbemServices.ExecQuery("SELECT * FROM Win32_PerfRawData_W3SVC_WebService WHERE Name = '_Total'", "WQL", _
                                          wbemFlagReturnImmediately + wbemFlagForwardOnly)

dim outval
outval = 0
For Each objItem In colItems
'	WScript.Echo "Name: " & objItem.Name
'	WScript.Echo "TotalMethodRequests: " & objitem.TotalMethodRequests
'	WScript.Echo "TotalMethodRequestsPersec: " & objItem.TotalMethodRequestsPersec

	outval = objItem.TotalMethodRequestsPersec
Next

' read last value from saved file
dim strFilename
strFilename = "TMP_requests_lv_" & strComputer & ".tmp"
dim DeltaValue

' create filesystem object
Set objFSO = CreateObject("Scripting.FileSystemObject")

' read previous value from temp file
Set ReadLV = objFSO.OpenTextFile(strFilename, 1)
dim intPreviousValue
if Not ReadLV.AtEndOfStream then
	intPreviousValue = ReadLV.Readline
else
	intPreviousValue = 0
end if
ReadLV.Close

' check if the last value is less than the current (rolled-over or no tmp file)
if intPreviousValue = 0 then
	' treat this as if this is the first time running it (no temp file)
	DeltaValue = outval
else
	' everything looks good, so get delta value and display that
	DeltaValue = outval - intPreviousValue
end if

' write latest value into temp file
Set WriteLV = objFSO.OpenTextFile(strFilename, 2, True)
WriteLV.WriteLine(outval)
WriteLV.Close

WScript.Echo "&label=" & time & "&value=" & DeltaValue
'wscript.echo "PREV: " & intPreviousValue & " Now: " & outval & " Delta: " & DeltaValue
