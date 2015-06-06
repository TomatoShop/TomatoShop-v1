<?php
/*
  $Id: admin_order_status_updated.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/

  require_once(realpath(dirname(__FILE__) . '/../../../'). '/includes/classes/sms_template.php');

  class toC_Sms_Template_admin_order_status_updated extends toC_Sms_Template {

/* Private variables */

    var $_template_name = 'admin_order_status_updated',
        $_keywords = array( '%%order_number%%',
                            '%%orders_status%%',
                            '%%store_name%%');

/* Class constructor */

    function toC_Sms_Template_admin_order_status_updated() {
      parent::toC_Sms_Template($this->_template_name);
    }


/* Private methods */

    function setData($order_number, $new_order_status, $billing_telephone){
      $this->_order_number = $order_number;
      $this->_new_order_status = $new_order_status;

      $this->addRecipient($billing_telephone);
    }

    function buildMessage() {

      $replaces = array($this->_order_number, $this->_new_order_status, STORE_NAME);

      $this->_sms_text = str_replace($this->_keywords, $replaces, $this->_content);
    }
  }
?>
