<?php
class Flow extends CI_Controller {

    function __construct() {      
        parent::__construct();
	}
	
	public function flow_value($sitecode=""){
		switch($sitecode){
			case 'PPN01':
							$URL="http://172.30.50.22/flow.sh";
							break;	
		}
		
		if(isset($URL)) {
			$this->url_cmd($URL);
		}else{
			echo "[]";
		}

	}//flow_value

	public function url_cmd($url){
		//echo "$url";

		/*
		// ดึงข้อมูลจากอีก server (แต่ server ปิดใช้งาน)
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,400);
 		curl_setopt($ch, CURLOPT_TIMEOUT, 400); //time
		curl_setopt($ch,CURLOPT_URL,$url);		
		$result = curl_exec($ch);
		if(!$result){
			echo "[]";	
		}
		curl_close($ch);
		*/
		
		echo "[]";	

	}//url_cmd


}//class