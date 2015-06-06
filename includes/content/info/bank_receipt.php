<?php
/*
  $Id: bank_receipt.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2005 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
  require_once("includes/classes/bank_receipt.php");
  require_once("ext/securimage/securimage.php");
  
  class osC_Info_Bank_Receipt extends osC_Template {

/* Private variables */

    var $_module = 'bank_receipt',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'bank_receipt.php',
        $_page_image = 'table_background_contact_us.gif';

/* Class constructor */

    function osC_Info_Bank_Receipt() {
      global $osC_Services, $osC_Language, $breadcrumb;

      $this->_page_title = $osC_Language->get('info_bank_receipt_heading');

      if ($osC_Services->isStarted('breadcrumb')) {
        $breadcrumb->add($osC_Language->get('breadcrumb_bank_receipt'), osc_href_link(FILENAME_INFO, $this->_module));
      }

      if ($_GET[$this->_module] == 'process') {
        $this->_process();
      }
      
      if ($_GET[$this->_module] == 'show_captcha') {
        $this->_show_captcha();
      }
    }

/* Private methods */
    function _show_captcha() {
      $img = new securimage();
      
      $img->show();
    }
	
    function _process() {
      global $osC_Language, $messageStack;
         
      if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = osc_sanitize_string($_POST['name']);
      } else {
        $messageStack->add('bank_receipt', $osC_Language->get('field_customer_name_error'));
      }
            
      if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email_address = osc_sanitize_string($_POST['email']);
        
        if (!osc_validate_email_address($email_address)) {
          $messageStack->add('bank_receipt', $osC_Language->get('field_customer_bank_receipt_email_error'));
        }
      } else {
        $messageStack->add('bank_receipt', $osC_Language->get('field_customer_bank_receipt_email_error'));
      }

      if (isset($_POST['telephone']) && !empty($_POST['telephone'])) {
        $telephone = osc_sanitize_string($_POST['telephone']);
      }
	  
      if (isset($_POST['amount']) && !empty($_POST['amount'])) {
        $amount = osc_sanitize_string($_POST['amount']);
      } else {
        $messageStack->add('bank_receipt', $osC_Language->get('field_bank_receipt_amount_error'));
      }   

      if (isset($_POST['bankname']) && !empty($_POST['bankname'])) {
        $bankname = osc_sanitize_string($_POST['bankname']);
      } else {
        $messageStack->add('bank_receipt', $osC_Language->get('field_bank_receipt_bankname_error'));
      }

      if (isset($_POST['receiptnumber']) && !empty($_POST['receiptnumber'])) {
        $receiptnumber = osc_sanitize_string($_POST['receiptnumber']);
      } else {
        $messageStack->add('bank_receipt', $osC_Language->get('field_bank_receipt_receiptnumber_error'));
      }
	  
	  if (isset($_POST['receiptdate_days']) && isset($_POST['receiptdate_months']) && isset($_POST['receiptdate_years'])) {
        $receiptdate = $_POST['receiptdate_years'] .'/' . $_POST['receiptdate_months'] .'/' . $_POST['receiptdate_days'];
        }

      if (isset($_POST['ordernumber']) && !empty($_POST['ordernumber'])) {
        $ordernumber = osc_sanitize_string($_POST['ordernumber']);
      } else {
        $messageStack->add('bank_receipt', $osC_Language->get('field_bank_receipt_ordernumber_error'));
      }
	  
      if (isset($_POST['description']) && !empty($_POST['description'])) {
        $description = osc_sanitize_string($_POST['description']);
      }
      
       if ( ACTIVATE_CAPTCHA == '1' ) {
        if (isset($_POST['captcha_code']) && !empty($_POST['captcha_code'])) {
          $securimage = new Securimage();
                  
          if ($securimage->check($_POST['captcha_code']) == false) {
            $messageStack->add('bank_receipt', $osC_Language->get('field_bank_receipt_captcha_check_error'));
          }
        } else {
          $messageStack->add('bank_receipt', $osC_Language->get('field_bank_receipt_captcha_check_error'));
        }  
      }
  
      if ( $messageStack->size('bank_receipt') === 0 ) {
	  
	$email_content = "
	<center>
	<table dir=rtl width=100% height=100% cellpadding=2 cellspacing=1><tr><td style='font-family:tahoma; font-size:12px; ' align=right >
" . $osC_Language->get('bank_receipt_name_title') . $name ."<br><br>
" . $osC_Language->get('bank_receipt_telephone_title') . $telephone ."<br><br>
" . $osC_Language->get('bank_receipt_email_address_title') . $email_address ."<br><br>
" . $osC_Language->get('bank_receipt_amount_title') . $amount ."<br><br>
" . $osC_Language->get('bank_receipt_bank_name_title') . $bankname ."<br><br>
" . $osC_Language->get('bank_receipt_receipt_number_title') . $receiptnumber ."<br><br>
" . $osC_Language->get('bank_receipt_receipt_date_title') . $receiptdate ."<br><br>
" . $osC_Language->get('bank_receipt_order_number_title') . $ordernumber ."<br><br>
" . $osC_Language->get('bank_receipt_description_title') . $description ."<br><br>
	</td></tr></table>
	</center>
";

        osc_email(STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $osC_Language->get('bank_receipt_email_subject'), $email_content, $name, $email_address);

        osc_redirect(osc_href_link(FILENAME_INFO, 'bank_receipt=success', 'AUTO', true, false));
		
     $email_content = '';
      } 
    }
 
  }
?>