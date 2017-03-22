<?php

if (($_SERVER['HTTP_USER_AGENT'] == "ESP8266HTTPClient") && isset($_SERVER['HTTP_ESP'])) {
    require_once("ESP.php");
    $App = new ESP();
    $App->loadDB();
    $App->element_id = $_SERVER['HTTP_ESP'];
    $App->element['IP'] = $App->getClientIP();
    $App->element['TIMESTAMP'] = $App->getTimestamp();
    $App->addElement();
    $App->saveDB();
    echo "{\"Result\": 0}";
}
else echo "{\"Result\": -1}";
?>
