<?php
/*
  $Id: sms_template.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/

  class toC_Sms_Template {
    var $_keywords = array(),
        $_template_name = '',
        $_status,
        $_content,
        $_sms_text,		
        $_recipients;

// class constructor
    function toC_Sms_Template($template_name) {
      global $osC_Database, $osC_Language;

      $Qtemplate = $osC_Database->query('select et.sms_templates_status, etd.sms_title, etd.sms_content from :table_sms_templates et, :table_sms_templates_description etd where et.sms_templates_id = etd.sms_templates_id and et.sms_templates_name = :sms_templates_name and etd.language_id = :language_id');

      $Qtemplate->bindValue(':sms_templates_name', $template_name);
      $Qtemplate->bindInt(':language_id', $osC_Language->getID());
      $Qtemplate->bindTable(':table_sms_templates', TABLE_SMS_TEMPLATES);
      $Qtemplate->bindTable(':table_sms_templates_description', TABLE_SMS_TEMPLATES_DESCRIPTION);
      $Qtemplate->execute();

      $this->_status = $Qtemplate->valueInt('sms_templates_status');
      $this->_content = $Qtemplate->value('sms_content');
    }

    function getSmsTemplate($template_name){
      $file_path = realpath(dirname(__FILE__) . '/../') . '/modules/sms_templates/' . $template_name . '.php';
      if(file_exists($file_path)){
        include_once($file_path);

        $sms_template_class = 'toC_Sms_Template_' . $template_name;
        return new $sms_template_class();
      }else{
        return null;
      }
    }

// class methods
    function getKeywords(){
      return $this->_keywords;
    }

    function addRecipient($mobile_number){
      $this->_recipients = $mobile_number;
    }
    
    function sendSms(){
     if($this->_status == '1'){
        	if ((SEND_SMS == '-1') OR (!preg_match("/^09([0-9]{9})$/",$this->_recipients))){
          	return false;
        	}
			
          require_once('sms.php');	
          $osC_Sms = new osC_Sms($this->_recipients,$this->_sms_text);
             
          $osC_Sms->send();
      }
    }

  }
?>
