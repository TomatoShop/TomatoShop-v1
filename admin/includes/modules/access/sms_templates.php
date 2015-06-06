<?php
/*
  $Id: sms_templates.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/

    class osC_Access_Sms_templates extends osC_Access {
    var $_module = 'sms_templates',
        $_group = 'tools',
        $_icon = 'sms_templates.png',
        $_title,
        $_sort_order = 750;

    function osC_Access_Sms_templates() {
      global $osC_Language;

      $this->_title = $osC_Language->get('access_sms_templates_title');
    }
  }
?>
