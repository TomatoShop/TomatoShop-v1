<?php
/*
  $Id: sms_templates.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/


  class toC_Sms_Templates_Admin {

    function getData($id) {
      global $osC_Database, $osC_Language;

      $Qtemplate = $osC_Database->query('select * from :table_sms_templates e, :table_sms_templates_description ed where e.sms_templates_id = ed.sms_templates_id and e.sms_templates_id = :sms_templates_id');

      $Qtemplate->bindTable(':table_sms_templates', TABLE_SMS_TEMPLATES);
      $Qtemplate->bindTable(':table_sms_templates_description', TABLE_SMS_TEMPLATES_DESCRIPTION);
      $Qtemplate->bindInt(':sms_templates_id', $id);
      $Qtemplate->execute();

      $data = $Qtemplate->toArray();

      $Qtemplate->freeResult();

      return $data;
    }

    function setStatus($id, $flag) {
      global $osC_Database;

      $Qtemplate = $osC_Database->query('update :table_sms_templates set sms_templates_status= :sms_templates_status where sms_templates_id = :sms_templates_id');
      $Qtemplate->bindTable(':table_sms_templates', TABLE_SMS_TEMPLATES);
      $Qtemplate->bindInt(':sms_templates_status', $flag);
      $Qtemplate->bindInt(':sms_templates_id', $id);
      $Qtemplate->setLogging($_SESSION['module'], $id);
      $Qtemplate->execute();

      return true;
    }

    function save($id = null, $data) {
      global $osC_Database, $osC_Language;

      $error = false;

      $osC_Database->startTransaction();

      $Qtemplate = $osC_Database->query('update :table_sms_templates set sms_templates_status = :sms_templates_status where sms_templates_id = :sms_templates_id');
      $Qtemplate->bindTable(':table_sms_templates', TABLE_SMS_TEMPLATES);
      $Qtemplate->bindInt(':sms_templates_id', $id);
      $Qtemplate->bindValue(':sms_templates_status', $data['sms_templates_status']);
      $Qtemplate->setLogging($_SESSION['module'], $id);
      $Qtemplate->execute();

      if ( !$osC_Database->isError() ) {

        foreach ($osC_Language->getAll() as $l) {
          $Qed = $osC_Database->query('update :table_sms_templates_description  set sms_title = :sms_title , sms_content = :sms_content  where sms_templates_id = :sms_templates_id and language_id = :language_id ');
          $Qed->bindTable(':table_sms_templates_description', TABLE_SMS_TEMPLATES_DESCRIPTION);
          $Qed->bindInt(':sms_templates_id', $id);
          $Qed->bindInt(':language_id', $l['id']);
          $Qed->bindValue(':sms_title', $data['sms_title'][$l['id']]);
          $Qed->bindValue(':sms_content', $data['sms_content'][$l['id']]);
          $Qed->setLogging($_SESSION['module'], $id);
          $Qed->execute();

          if ( $osC_Database->isError() ) {
            $error = true;
            break;
          }
        }
      }

      if ( $error === false ) {
          $osC_Database->commitTransaction();

          return true;
      }

      $osC_Database->rollbackTransaction();

      return false;
    }

    function getKeywords($sms_templates_name) {
      include('../includes/modules/sms_templates/' . $sms_templates_name.'.php');

      $module = 'toC_Sms_Template_' . $sms_templates_name;
      $module = new $module();

      $keywords = $module->getKeywords();
      return $keywords;
    }

}
?>
