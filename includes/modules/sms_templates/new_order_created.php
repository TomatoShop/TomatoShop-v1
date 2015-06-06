<?php
/*
  $Id: new_order_created.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/

  require_once(realpath(dirname(__FILE__) . '/../../../'). '/includes/classes/sms_template.php');

  class toC_Sms_Template_new_order_created extends toC_Sms_Template {

/* Private variables */

    var $_template_name = 'new_order_created',
        $_keywords = array( '%%order_number%%',
                            '%%date_ordered%%',
                            '%%payment_method%%',
                            '%%customer_name%%',
                            '%%order_status%%',
                            '%%store_name%%');

/* Class constructor */

    function toC_Sms_Template_new_order_created() {
      parent::toC_Sms_Template($this->_template_name);
    }


/* Private methods */

  function setData($order_id){
      $this->_order_id = $order_id;
  }

    function buildMessage() {
    global $osC_Database, $osC_Language, $osC_Currencies;

      $Qorder = $osC_Database->query('select * from :table_orders where orders_id = :orders_id limit 1');
      $Qorder->bindTable(':table_orders', TABLE_ORDERS);
      $Qorder->bindInt(':orders_id', $this->_order_id);
      $Qorder->execute();

      if ($Qorder->numberOfRows() === 1) {

        $order_number = $this->_order_id;
        $date_ordered = osC_DateTime::getShort();
        $payment_method = $Qorder->value('payment_method');
		
        $customer_name = $Qorder->value('billing_name');
        $customer_phone = $Qorder->value('billing_telephone');


        $Qstatus = $osC_Database->query('select orders_status_name from :table_orders_status where orders_status_id = :orders_status_id and language_id = :language_id');
        $Qstatus->bindTable(':table_orders_status', TABLE_ORDERS_STATUS);
        $Qstatus->bindInt(':orders_status_id', $Qorder->valueInt('orders_status'));
        $Qstatus->bindInt(':language_id', $osC_Language->getID());
        $Qstatus->execute();

        $order_status = $Qstatus->value('orders_status_name');

        unset($Qstatus);


        $replaces = array($order_number, $date_ordered, $payment_method, $customer_name, $order_status, STORE_NAME);

        $this->_sms_text = str_replace($this->_keywords, $replaces, $this->_content);
		
		$this->addRecipient($customer_phone);
		
      }
      unset($Qorder);
    }
  }
?>
