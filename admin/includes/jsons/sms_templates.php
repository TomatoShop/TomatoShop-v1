<?php
/*
  $Id: sms_templates.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/
  require('includes/classes/sms_templates.php');

  class toC_Json_Sms_Templates {
        
    function listSmsTemplates() {
      global $toC_Json, $osC_Database, $osC_Language;
      
      $Qtemplates = $osC_Database->query('select * from :table_sms_templates e, :table_sms_templates_description ed where e.sms_templates_id = ed.sms_templates_id and ed.language_id = :language_id order by e.sms_templates_name ');
      $Qtemplates->bindInt(':language_id', $osC_Language->getID());
      $Qtemplates->bindTable(':table_sms_templates', TABLE_SMS_TEMPLATES);
      $Qtemplates->bindTable(':table_sms_templates_description', TABLE_SMS_TEMPLATES_DESCRIPTION);
      $Qtemplates->execute();
        
      $records = array();     
      while ( $Qtemplates->next() ) {
        $records[] = array(
          'sms_templates_id' => $Qtemplates->valueInt('sms_templates_id'),
          'sms_templates_name' => $Qtemplates->value('sms_templates_name'),
          'sms_title' => $Qtemplates->value('sms_title'),
          'sms_templates_status' => $Qtemplates->value('sms_templates_status')
        );           
      }
      $Qtemplates->freeResult();         
       
      $response = array(EXT_JSON_READER_TOTAL => sizeof($records),
                        EXT_JSON_READER_ROOT => $records);
     
      echo $toC_Json->encode($response);
    }          
    
    function setStatus() {
      global $toC_Json, $osC_Language;
        
      if ( toC_Sms_Templates_Admin::setStatus($_REQUEST['sms_templates_id'], ( isset($_REQUEST['flag']) ? $_REQUEST['flag'] : null) ) ) {
        $response = array('success' => true, 'feedback' => $osC_Language->get('ms_success_action_performed') );
      } else {
        $response = array('success' => false, 'feedback' => $osC_Language->get('ms_error_action_not_performed'));
      }
      
      echo $toC_Json->encode($response);
    }
    
    function loadSmsTemplate() {
      global $toC_Json, $osC_Database;
     
      $sms_templates_id = ( isset($_REQUEST['sms_templates_id']) && is_numeric($_REQUEST['sms_templates_id']) ) ? $_REQUEST['sms_templates_id'] : null;
      
      $data = toC_Sms_Templates_Admin::getData($sms_templates_id);
      
      $Qtemplate = $osC_Database->query('select * from :table_sms_templates_description where sms_templates_id= :sms_templates_id');
      $Qtemplate->bindTable(':table_sms_templates_description', TABLE_SMS_TEMPLATES_DESCRIPTION);
      $Qtemplate->bindInt(':sms_templates_id', $sms_templates_id);
      $Qtemplate->execute();
      
      while ($Qtemplate->next()) {
        $data["sms_title[" . $Qtemplate->valueInt('language_id') . "]"] = $Qtemplate->value('sms_title');
        $data["sms_content[" . $Qtemplate->valueInt('language_id') . "]"] = $Qtemplate->value('sms_content');
      }
      
      $response = array('success' => true, 'data' => $data);
       
      echo $toC_Json->encode($response);    
    }
   
    function getVariables() {
      global $toC_Json;

      $keywords = toC_Sms_Templates_Admin:: getKeywords($_REQUEST['sms_templates_name']);
      
      $records = array();
      foreach ($keywords as $key => $value) {
        $records[] = array('id' => $key, 'value' => $value);
      }

      $response = array(EXT_JSON_READER_ROOT => $records);
      
      echo $toC_Json->encode($response);
    }
    
    function saveSmsTemplate() {
      global $toC_Json, $osC_Language;
      
      $sms_templates_id = ( isset($_REQUEST['sms_templates_id']) && is_numeric($_REQUEST['sms_templates_id']) )? $_REQUEST['sms_templates_id'] : null;
      
      $data = array('sms_templates_status' => $_REQUEST['sms_templates_status'],
                    'sms_title' => $_REQUEST['sms_title'],
                    'sms_content' => $_REQUEST['sms_content']);
                         
      $error = false;
      $feedback = array();

      foreach ( $data['sms_title'] as $key => $value ) {
        if ( empty($value) ) {
          $feedback[] = $osC_Language->get('ms_error_sms_title_empty').'('.$osC_Language->getData($key, 'name').')';
          $error = true;
        }
      }
      
     foreach ( $data['sms_content'] as $key => $value ) {
       if ( empty($value) ) {
         $feedback[] = $osC_Language->get('ms_error_sms_content_empty').'('.$osC_Language->getData($key, 'name').')';
         $error = true;
       }
     }
     
     if ( $error === false ) {
       if ( toC_Sms_Templates_Admin::save($sms_templates_id, $data) ) {
         $response = array('success' => true, 'feedback' => $osC_Language->get('ms_success_action_performed'));
       } else {
         $response = array('success' => false, 'feedback' => $osC_Language->get('ms_error_action_not_performed'));
       }
     } else {
       $response['success'] = false;
       $response['feedback'] = $osC_Language->get('ms_error_action_not_performed') . '<br />' . implode('<br />', $feedback);
     }
     
     echo $toC_Json->encode($response);
    }
  }
?>
