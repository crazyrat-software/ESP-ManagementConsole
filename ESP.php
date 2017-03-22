<?php

class ESP {

    public $db = array();
    public $element_id = "";
    public $element = array("IP" => "", "TIMESTAMP" => "");

    function getClientIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function getTimestamp() {
	$date = date("Y-m-d H:i:s");
	return strtotime($date);
    }
    
    public function expireDB() {
	$time = $this->getTimestamp();
	foreach ($this->db as $key => $val) {
	    if (round(abs($time - $this->db[$key]['TIMESTAMP']), 0) >= 70) unset($this->db[$key]);
	}
    }
    
    public function loadDB() {
	$s = file_get_contents('store');
	if ($s) {
	    $this->db = unserialize(file_get_contents('store'));
	    return sizeof($this->db);
	}
	else {
	    $this->db = array();
	    return 0;
	}
    }

    public function addElement() {
	$this->db[$this->element_id] = $this->element;
    }

    public function showDB() {
	echo "<pre>";
	print_r($this->db);
    }

    public function showElement() {
	echo "<pre>";
	print_r($this->element);
    }

    public function saveDB() {
	file_put_contents('store', serialize($this->db));
    }

    public function esp_getGPIO() {
	
    }
}
?>