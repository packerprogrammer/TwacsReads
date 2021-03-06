<?php

//$readserial = $_GET["serial"];

$conn = oci_connect('dcsi', 'dcsi', '10.6.16.53:1521/ACLARA');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
// Prepare the statement
$stid = oci_parse($conn, "select mm.metermitdata1 raw_read, mm.metermitdata1*mc.meterconvkh/mc.meterconvdivisor kwh_read from metermitresponselogdata mm, meterconv mc, meteraccts ma where mm.serialnumber = ma.serialnumber and mc.metertype = ma.metertype and mm.metermitreaddt > trunc(sysdate) and mm.metermitdata1id = 111 and mm.serialnumber = 8667290 union select mm.metertcresptotalconsumpt raw_read, mm.metertcresptotalconsumpt*mc.meterconvkh/mc.meterconvdivisor kwh_read from metertcresponselog mm, meterconv mc, meteraccts ma where mm.serialnumber = ma.serialnumber and mc.metertype = ma.metertype and mm.metertcrespcurdatetime > trunc(sysdate) and mm.serialnumber = 8667290");
if (!$stid) {
    $e = oci_error($conn);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Perform the logic of the query
$r = oci_execute($stid);
if (!$r) {
    $e = oci_error($stid);
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

// Fetch the results of the query

print "<table border='1'>\n";
while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
    print "<tr>\n";
    foreach ($row as $item) {
        print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
    }
    print "</tr>\n";
}
print "</table>\n";

oci_free_statement($stid);
oci_close($conn);

?>
