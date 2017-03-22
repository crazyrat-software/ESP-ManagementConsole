<?php

require_once("header.php");
require_once("ESP.php");

echo "<pre>";
$App = new ESP();
$App->loadDB();
$App->expireDB();
$App->saveDB();
?>
<h3><a href="listRegistered.php">Active modules</a> | All IO</h3>
<table>
<tr><th>Module ID</th><th>GPIO</th><th>Mode</th><th>Value</th></tr>
<?php
foreach ($App->db as $key => $val) {
    $module = $key;
    $json = json_decode($val["getGPIO"], true); //json to array
    $json2= json_decode($val["getModeStr"], true);
    $gpio = $json["GPIO"];
    foreach ($gpio as $k => $v) {
	echo "<tr><td>".$module."</td><td>".$k."</td><td>".$json2["GPIO"][$k]."</td><td>".$v."</td></tr>";
    }
}
?>
</table>


</body>
</html>

