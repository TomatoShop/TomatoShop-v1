<?php
###############################################################################
###############################################################################
##                                                                           ##
##  $Id: bpi.php $                                                           ##
##  PASARGAD BANK PAYMENT FOR PERSIAN TOMATOCART                             ##
##                                                                           ##
##  HTTP://WWW.TOMATOSHOP.IR                                                 ##
##  Copyright (c) 2011 TOMATOSHOP.IR                                         ##
##  AUTHOR Ali Masooumi ( masooumi[at]gmail[dot]com )                        ##
##  BPI VERSION 0.1 beta                                                     ##
##                                                                           ##
###############################################################################
###############################################################################
    require_once('ext/bpiclass/RSAProcessor.class.php');

  class osC_Payment_bpi extends osC_Payment {
    var $_title,
        $_code = 'bpi',
        $_status = false,
        $_sort_order,
        $_order_id;

    function osC_Payment_bpi() {
      global $order, $osC_Database, $osC_Language, $osC_ShoppingCart;

      $this->_title = $osC_Language->get('payment_bpi_title');
      $this->_method_title = $osC_Language->get('payment_bpi_method_title');
      $this->_status = (MODULE_PAYMENT_BPI_STATUS == '1') ? true : false;
      $this->_sort_order = MODULE_PAYMENT_BPI_SORT_ORDER;

      $this->form_action_url = 'https://pep.shaparak.ir/gateway.aspx';

      if ($this->_status === true) {
        if ((int)MODULE_PAYMENT_BPI_ORDER_STATUS_ID > 0) {
          $this->order_status = MODULE_PAYMENT_BPI_ORDER_STATUS_ID;
        }

        if ((int)MODULE_PAYMENT_BPI_ZONE > 0) {
          $check_flag = false;

          $Qcheck = $osC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
          $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
          $Qcheck->bindInt(':geo_zone_id', MODULE_PAYMENT_BPI_ZONE);
          $Qcheck->bindInt(':zone_country_id', $osC_ShoppingCart->getBillingAddress('country_id'));
          $Qcheck->execute();

          while ($Qcheck->next()) {
            if ($Qcheck->valueInt('zone_id') < 1) {
              $check_flag = true;
              break;
            } elseif ($Qcheck->valueInt('zone_id') == $osC_ShoppingCart->getBillingAddress('zone_id')) {
              $check_flag = true;
              break;
            }
          }

          if ($check_flag === false) {
            $this->_status = false;
          }
        }
      }
    }

    function selection() {
      return array('id' => $this->_code,
                   'module' => $this->_method_title);
    }


	//
	    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      global $osC_Language, $osC_CreditCard;
	  
    $this->_order_id = osC_Order :: insert(ORDERS_STATUS_PREPARING);
	$order = $this->_order_id;  
      $confirmation = array('title' => $this->_method_title,
                           'fields' => array(array('title' => $osC_Language->get('payment_bpi_description'))));

      return $confirmation;
    }

    function process_button() {
      global $osC_Currencies, $osC_ShoppingCart, $osC_Language, $osC_Database;
	  
      if (MODULE_PAYMENT_BPI_CURRENCY == 'Selected Currency') {
        $currency = $osC_Currencies->getCode();
      } else {
        $currency = MODULE_PAYMENT_BPI_CURRENCY;
      }

      $amount = round($osC_Currencies->formatRaw($osC_ShoppingCart->getTotal(), $currency), 2);
      $order = $this->_order_id;

$processor = new RSAProcessor('ext/bpiclass/'.MODULE_PAYMENT_BPI_PRIVATE_KEY_FOLDER.'/certificate.xml',RSAKeyType::XMLFile); // Private Key
$merchantCode = MODULE_PAYMENT_BPI_MERCHANT_CODE;
$terminalCode = MODULE_PAYMENT_BPI_TERMINAL_CODE;
$amount = $amount;
$redirectAddress = osc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', null, null, true);
$invoiceNumber = $order;
$timeStamp = date("Y/m/d H:i:s");
$invoiceDate = date("Y/m/d H:i:s");
$action = "1003";
$data = "#". $merchantCode ."#". $terminalCode ."#". $invoiceNumber 
."#". $invoiceDate."#". $amount ."#". $redirectAddress ."#". $action ."#".
 $timeStamp ."#";
 $data = sha1($data,true);
 $data = $processor->sign($data); // digital sign
 $result = base64_encode($data); // base64_encode

      $process_button_string = osc_draw_hidden_field('invoiceNumber', $invoiceNumber).
                               osc_draw_hidden_field('invoiceDate', $invoiceDate).
							   osc_draw_hidden_field('amount', $amount).
							   osc_draw_hidden_field('terminalCode', MODULE_PAYMENT_BPI_TERMINAL_CODE).
							   osc_draw_hidden_field('merchantCode', MODULE_PAYMENT_BPI_MERCHANT_CODE).
							   osc_draw_hidden_field('redirectAddress', osc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', null, null, true)).
							   osc_draw_hidden_field('timeStamp', $timeStamp).
                               osc_draw_hidden_field('action', $action).
                               osc_draw_hidden_field('sign', $result);

      return $process_button_string;
    }
	
    function get_error() {
      return false;
    }
    function process() {
      global $osC_Customer, $osC_Language, $osC_Currencies, $osC_ShoppingCart, $_POST, $_GET, $osC_Database, $messageStack;
	  
      // get data from pasargad
      $tref = $_GET['tref']; //TransactionReferenceID
      $iNumber = $_GET['iN']; //invoiceNumber
      $iDate = $_GET['iD']; //invoiceDate
	  $this->_order_id = osC_Order :: insert(ORDERS_STATUS_PREPARING);
	  $order = $this->_order_id;
	  
      if (MODULE_PAYMENT_BPI_CURRENCY == 'Selected Currency') {
        $currency = $osC_Currencies->getCode();
      } else {
        $currency = MODULE_PAYMENT_BPI_CURRENCY;
      }

      $amount = round($osC_Currencies->formatRaw($osC_ShoppingCart->getTotal(), $currency), 2);	  
	  
      require_once('ext/bpiclass/parser.php');	  
	  $result = post2https($tref,'https://pep.shaparak.ir/CheckTransactionResult.aspx');
	  $array = makeXMLTree($result);
	  
	  $state=strtolower($array["resultObj"]["result"]);
      $action=$array["resultObj"]["action"];
      $invoiceNumber=$array["resultObj"]["invoiceNumber"];
	  $invoiceDate=$array["resultObj"]["invoiceDate"];
      $merchantCode=$array["resultObj"]["merchantCode"];
      $terminalCode=$array["resultObj"]["terminalCode"];
      $traceNumber=$array["resultObj"]["traceNumber"];
      $referenceNumber=$array["resultObj"]["referenceNumber"];
      $transactionDate=$array["resultObj"]["transactionDate"];

      if  (($state=="true") AND ($action =="1003") AND ($merchantCode == MODULE_PAYMENT_BPI_MERCHANT_CODE) AND ($terminalCode == MODULE_PAYMENT_BPI_TERMINAL_CODE) AND ($invoiceDate == $iDate) AND ($invoiceNumber == $order)) 
	  {
	    // here we update our order state
		$this->_order_id = osC_Order :: insert();
		$comments = $osC_Language->get('payment_bpi_transaction_id') . '[' . $tref . ']' . $osC_Language->get('payment_bpi_reference_id') . '[' . $referenceNumber . ']' ;
		osC_Order :: process($this->_order_id, $this->order_status,$comments);	
		
	    // here we save our database
		$osC_Database->simpleQuery("insert into `" . DB_TABLE_PREFIX . "online_transactions`
					  		(orders_id,receipt_id,transaction_method,transaction_date,transaction_amount,transaction_id) values
		                    ('$order','$referenceNumber','bpi','$transactionDate','$amount','$tref')
					         ");
	    //
	    $Qtransaction = $osC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
	    $Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
	    $Qtransaction->bindInt(':orders_id', $order);
	    $Qtransaction->bindInt(':transaction_code', 1);
	    $Qtransaction->bindValue(':transaction_return_value', $referenceNumber);
	    $Qtransaction->bindInt(':transaction_return_status', 1);
	    $Qtransaction->execute();
 		
		} else {
		osC_Order :: remove($this->_order_id);
		if (($state=="false") and ($merchantCode == MODULE_PAYMENT_BPI_MERCHANT_CODE) and ($terminalCode == MODULE_PAYMENT_BPI_TERMINAL_CODE) and ($invoiceDate == $iDate) and ($invoiceNumber == $order)) {
		
        $messageStack->add_session('checkout', $osC_Language->get('payment_bpi_unsuccessful_payment'), 'error');		 
		
		} elseif (($state=="false")  and (($merchantCode != MODULE_PAYMENT_BPI_MERCHANT_CODE) OR ($terminalCode != MODULE_PAYMENT_BPI_TERMINAL_CODE) OR ($invoiceDate != $iDate) OR ($invoiceNumber != $order))){
		
        $messageStack->add_session('checkout', $osC_Language->get('payment_bpi_contradictory_in_information'), 'error');		
		
        } else {
		$messageStack->add_session('checkout', $osC_Language->get('payment_bpi_payment_not_confirmed'), 'error');
		}
		osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true)); 
      }  	  
    }
    function callback() {
      global $osC_Database;
//
    }
  }
?>