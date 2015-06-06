<?php
/*
  $Id: sms.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/

  class osC_Sms {
    var $_to,
        $_body,
        $_username = SMS_GETWAY_USERNAME,
        $_password = SMS_GETWAY_PASSWORD,
        $_from = SMS_GETWAY_FROM_NUMBER,
        $_flash = false;

    function osC_Sms($to_mobile_number = null,$sms_text) {
      if ( !empty($to_mobile_number) ) {
        $this->_to = $to_mobile_number;
      }
        $this->_body = $sms_text;
    }
    
    function send() {

     if (SMS_GATEWAY == 'SanaPayamak.Com - WebService') {

        return $this->sendSanaPayamakWS();
        
      } else {
        
	return $this->sendSanaPayamakUrl();	
	
      }
      
    }
	
    function sendSanaPayamakUrl() 
	{
	$response = false;
	$purl = curl_init('http://panel.sanapayamak.com/post/sendsms.ashx?from='.$this->_from.'&to='.$this->_to.'&text='.urlencode($this->_body).'&password='.$this->_password.'&username='.$this->_username.'');
	curl_setopt($purl, CURLOPT_RETURNTRANSFER, 1);
	$sms_response = curl_exec($purl);
	curl_close($purl);
	$result = explode("-",$sms_response);
        
    if($result[0] == "1") {
        $response = true;
    }
	return $response;
	}

    function sendSanaPayamakWS() 
	{
	$response = false;
	ini_set("soap.wsdl_cache_enabled", "0");
	try {
		$client = new SoapClient('http://panel.sanapayamak.com/post/send.asmx?WSDL');	
		$parameters = array('username' => $this->_username,
					'password' => $this->_password,
					'from' => $this->_from,
					'to' => array('string' => $this->_to),
					'text' => $this->_body,
					'isflash' => $this->_flash,
					'udh' => "",
					'recId' => array(0),
					'status' => 0x0);
					
	$SendSmsResult =  $client->SendSms($parameters)->SendSmsResult;
	 } catch (SoapFault $ex) {
		echo $ex->faultstring;
	}

	if($SendSmsResult == "1") {
		$response = true;	
	}
	return $response;
	}
 
  }
?>