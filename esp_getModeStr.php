<?php

    require_once("ESP.php");

    $App = new ESP();
    $App->loadDB();
    $App->expireDB();
    foreach ($App->db as $key => $val) {
	$json = file_get_contents("http://".$val['IP']."/getModeStr");
	$App->db[$key]['getModeStr'] = $json;
	$App->addElement();
	if ($json) { $obj = json_decode($json); }
    }
    $App->saveDB();
    //print_r($App->db);
    echo "getModeStr Objects: ".$obj->GPIOCount."\n";
?>