<?php

class ESP_Update {

    public $file = "";
    public $path = "/var/www/website/esp/firmware/";
    public $MAC = array(
        "5C:CF:7F:2C:E4:D0" => "1.1",
        "18:FE:AA:AA:AA:BB" => ""
    );


    function __construct() {
	header('Content-type: text/plain; charset=utf8', true);
	file_put_contents('debug', var_export($_SERVER, true));
	
	if(!$this->checkHeader('HTTP_USER_AGENT', 'ESP8266-http-Update')) {
	    file_put_contents('debug', "ERROR: Wrong User Agent!\n", FILE_APPEND);
	    header($_SERVER["SERVER_PROTOCOL"].' 403 Forbidden (Agent)', true, 403);
	    echo "Unauthorized!\n";
	    die();
	}

	if(
	    !$this->checkHeader('HTTP_X_ESP8266_STA_MAC') ||
	    !$this->checkHeader('HTTP_X_ESP8266_AP_MAC') ||
	    !$this->checkHeader('HTTP_X_ESP8266_FREE_SPACE') ||
	    !$this->checkHeader('HTTP_X_ESP8266_SKETCH_SIZE') ||
	    //!$this->checkHeader('HTTP_X_ESP8266_SKETCH_MD5') ||
	    !$this->checkHeader('HTTP_X_ESP8266_VERSION') ||
	    !$this->checkHeader('HTTP_X_ESP8266_CHIP_SIZE') ||
	    !$this->checkHeader('HTTP_X_ESP8266_SDK_VERSION')
	) {
	    file_put_contents('debug', "ERROR: Wrong Headers!\n", FILE_APPEND);
	    header($_SERVER["SERVER_PROTOCOL"].' 403 Forbidden (Header)', true, 403);
	    echo "Unauthorized!\n";
	    die();
	}

	$this->checkMAC();

	$this->checkVERSION();

	$this->file = $this->path."application.".$this->MAC[$_SERVER['HTTP_X_ESP8266_STA_MAC']].".bin";
	file_put_contents('debug', "INFO: Sending file: ".$this->file."\n", FILE_APPEND);
	$this->sendFile($this->file);

//	header($_SERVER["SERVER_PROTOCOL"].' 403 Forbidden', true, 403);
//	echo "Unauthorized!\n";
//	die();
    }

    public function checkMAC() {
	if(!isset($this->MAC[$_SERVER['HTTP_X_ESP8266_STA_MAC']])) {
	    file_put_contents('debug', "ERROR: Wrong MAC!\n", FILE_APPEND);
	    header($_SERVER["SERVER_PROTOCOL"].' 403 Forbidden (MAC)', true, 403);
	    echo "Unauthorized!\n";
	    die();
	}
    }

    public function checkVERSION() {
	if($this->MAC[$_SERVER['HTTP_X_ESP8266_STA_MAC']] == $_SERVER['HTTP_X_ESP8266_VERSION']) {
	    file_put_contents('debug', "INFO: No Updates!\n", FILE_APPEND);
	    header($_SERVER["SERVER_PROTOCOL"].' 304 Not Modified', true, 304);
	    die();
	}
    }

    public function checkHeader($name, $value = false) {
	if(!isset($_SERVER[$name])) {
        	return false;
        }
        if($value && $_SERVER[$name] != $value) {
		return false;
        }
        return true;
    }

    public function sendFile($path) {
        header($_SERVER["SERVER_PROTOCOL"].' 200 OK', true, 200);
        header('Content-Type: application/octet-stream', true);
        header('Content-Disposition: attachment; filename='.basename($path));
        header('Pragma: public');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: '.filesize($path), true);
        header('x-MD5: '.md5_file($path), true);
        ob_clean();
        flush();
        readfile($path);
    }


}
?>