<?php
###############################################################################
###############################################################################
##                                                                           ##
##  $Id: saman.php $                                                         ##
##  SAMAN BANK PAYMENT FOR PERSIAN TOMATOCART                                ##
##                                                                           ##
##  HTTP://WWW.TOMATOSHOP.IR                                                 ##
##  Copyright (c) 2011 TOMATOSHOP.IR                                         ##
##  AUTHOR Ali Masooumi ( masooumi[at]gmail[dot]com )                        ##
##  SAMAN VERSION 0.1                                                        ##
##                                                                           ##
###############################################################################
###############################################################################

  class osC_Payment_saman extends osC_Payment {
    var $_title,
        $_code = 'saman',
        $_status = false,
        $_sort_order,
        $_order_id;

    function osC_Payment_saman() {
      global $order, $osC_Database, $osC_Language, $osC_ShoppingCart;

      $this->_title = $osC_Language->get('payment_saman_title');
      $this->_method_title = $osC_Language->get('payment_saman_method_title');
      $this->_status = (MODULE_PAYMENT_SAMAN_STATUS == '1') ? true : false;
      $this->_sort_order = MODULE_PAYMENT_SAMAN_SORT_ORDER;

      $this->form_action_url = 'https://sep.shaparak.ir/Payment.aspx';

      if ($this->_status === true) {
        if ((int)MODULE_PAYMENT_SAMAN_ORDER_STATUS_ID > 0) {
          $this->order_status = MODULE_PAYMENT_SAMAN_ORDER_STATUS_ID;
        }

        if ((int)MODULE_PAYMENT_SAMAN_ZONE > 0) {
          $check_flag = false;

          $Qcheck = $osC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
          $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
          $Qcheck->bindInt(':geo_zone_id', MODULE_PAYMENT_SAMAN_ZONE);
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

	    function pre_confirmation_check() {
      return false;
    }

    function confirmation() {
      global $osC_Language, $osC_CreditCard;
	  
      $this->_order_id = osC_Order :: insert(ORDERS_STATUS_PREPARING);
	  
      $confirmation = array('title' => $this->_method_title,
                           'fields' => array(array('title' => $osC_Language->get('payment_saman_description'))));

      return $confirmation;
    }

    function process_button() {
      global $osC_Currencies, $osC_ShoppingCart, $osC_Language;


	  
      if (MODULE_PAYMENT_SAMAN_CURRENCY == 'Selected Currency') {
        $currency = $osC_Currencies->getCode();
      } else {
        $currency = MODULE_PAYMENT_SAMAN_CURRENCY;
      }

      $amount = round($osC_Currencies->formatRaw($osC_ShoppingCart->getTotal(), $currency), 2);
      $order = $this->_order_id;

      $process_button_string = osc_draw_hidden_field('MID', MODULE_PAYMENT_SAMAN_MERCHANT_ID).
                               osc_draw_hidden_field('ResNum', $order).
                               osc_draw_hidden_field('RedirectURL', osc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', null, null, true)).
                               osc_draw_hidden_field('Amount', $amount);

      return $process_button_string;
    }
	
    function get_error() {
      return false;
    }
    function process() {
      global $osC_Database, $osC_Customer, $osC_Currencies, $osC_ShoppingCart, $_POST, $_GET, $osC_Language, $messageStack;
	  
	  require_once('ext/lib/nusoap.php');
	  
	  $ResNum = $_POST['ResNum'];
	  $RefNum = $_POST['RefNum'];
      $State = $_POST['State'];
	  // get amount & order Id
	  if (MODULE_PAYMENT_SAMAN_CURRENCY == 'Selected Currency') {
        $currency = $osC_Currencies->getCode();
      } else {
        $currency = MODULE_PAYMENT_SAMAN_CURRENCY;
      }

      $amount = round($osC_Currencies->formatRaw($osC_ShoppingCart->getTotal(), $currency), 2);
	  //
	  		
	if(($State!='OK') and ($RefNum=='')){
	// here we update our database
	   osC_Order :: remove($this->_order_id);
	
        $messageStack->add_session('checkout', check_saman_state_error($State), 'error');
		
        osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true));		
         } else {
    $soapclient = new nusoap_client('https://sep.shaparak.ir/payments/referencepayment1.asmx?WSDL','wsdl');
//	$soapclient->debug_flag=true;
	$soapProxy = $soapclient->getProxy();
//	if($err=$soapclient->getError())
//		echo $err ;
//	echo $soapclient->debug_str;
		//	$i = 5; //to garantee the connection and verify, this process should be repeat maximum 5 times
		//	do{
	$res=  $soapProxy->verifyTransaction($RefNum,MODULE_PAYMENT_SAMAN_MERCHANT_ID);//reference number and sellerid	
			//		$i -= 1;
            //  } while((!$res) and ($i>0));
	$err = $soapProxy->getError();
			if ($err) {
					osC_Order :: remove($this->_order_id);
					$messageStack->add_session('checkout', 'خطا در تایید تراکنش ، مبلغ تراکنش با موفقیت به حساب شما برگشت داده خواهد شد.', 'error');
	   			    osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true));
				die();
				}		
	    if( $res <= 0 ) 
		{
		// this is a unsucccessfull payment
	   // we update our DataBase
        osC_Order::remove($this->_order_id);
        $messageStack->add_session('checkout', check_saman_res_error($res), 'error');
		
        osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true));        
       } 
      else 
	  {
	  if($res == $amount)
       {
	   // this is a succcessfull payment
	   // we update our DataBase
					// insert ref id in database
					$osC_Database->simpleQuery("insert into `" . DB_TABLE_PREFIX . "online_transactions`
					  		(orders_id,receipt_id,transaction_method,transaction_date,transaction_amount,transaction_id) values
		                    ('$ResNum','$RefNum','saman','" . date("YmdHis") . "','$amount','$RefNum')
					  ");
					//
						$Qtransaction = $osC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
						$Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
						$Qtransaction->bindInt(':orders_id', $ResNum);
						$Qtransaction->bindInt(':transaction_code', 1);
						$Qtransaction->bindValue(':transaction_return_value', $RefNum);
						$Qtransaction->bindInt(':transaction_return_status', 1);
						$Qtransaction->execute();
						//
						$this->_order_id = osC_Order :: insert();
						$comments = $osC_Language->get('payment_saman_method_authority') . '[' . $RefNum . ']';
						osC_Order :: process($this->_order_id, $this->order_status, $comments);
     } 
	 else
	 {
					osC_Order :: remove($this->_order_id);
					$messageStack->add_session('checkout', 'خطا در تاييد مبلغ تراکنش ، مبلغ تراکنش با موفقيت به حساب شما برگشت داده خواهد شد.', 'error');
	   			    osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true));

	        }
	      } 
        }
      }
    
    function callback() {
      global $osC_Database;
//
    }
  }
/********************************************** s t a t e   e r r o r ***********************************************/
function check_saman_state_error($State){
	
	switch($State){
		
		case 'Canceled By User' :
			$pay_error= "تراکنش توسط خريدار کنسل شده است.";
			break;
		case 'Invalid Amount' :
			$pay_error= "مبلغ سند برگشتي، از مبلغ تراکنش اصلي بيشتر است.";
			break;
		case 'Invalid Transaction' :
			$pay_error= "درخواست برگشت يک تراکنش رسيده است، در حالي که تراکنش اصلي پيدا نمي شود.";
			break;
		case 'Invalid Card Number' :
			$pay_error= "شماره کارت اشتباه است.";
			break;
		case 'No Such Issuer' :
			$pay_error= "چنين صادر کننده کارتي وجود ندارد.";
			break;
		case 'Expired Card Pick Up' :
			$pay_error= "از تاريخ انقضاي کارت گذشته است و کارت ديگر معتبر نيست.";
			break;
		case 'Allowable PIN Tries Exceeded Pick Up' :
			$pay_error= "رمز کارت (PIN) 3 مرتبه اشتباه وارد شده است در نتيجه کارت غير فعال خواهد شد.";
			break;
		case 'Incorrect PIN' :
			$pay_error= "خريدار رمز کارت (PIN) را اشتباه وارد کرده است.";
			break;
		case 'Exceeds Withdrawal Amount Limit' :
			$pay_error= "مبلغ بيش از سقف برداشت مي باشد.";
			break;
		case 'Transaction Cannot Be Completed' :
			$pay_error= "تراکنش Authorize شده است ( شماره PIN و PAN درست هستند) ولي امکان سند خوردن وجود ندارد.";
			break;
		case 'Response Received Too Late' :
			$pay_error= "تراکنش در شبکه بانکي Timeout خورده است.";
			break;
		case 'Suspected Fraud Pick Up' :
			$pay_error= "خريدار يا فيلد CVV2 و يا فيلد ExpDate را اشتباه زده است. ( يا اصلا وارد نکرده است)";
			break;
		case 'No Sufficient Funds' :
			$pay_error= "موجودي به اندازه کافي در حساب وجود ندارد.";
			break;
		case 'Issuer Down Slm' :
			$pay_error= "سيستم کارت بانک صادر کننده در وضعيت عملياتي نيست.";
			break;
		case 'TME Error' :
			$pay_error= "خطای ايجاد شده قابل شناسايى نيست. لطفا با مديريت سايت تماس بگيريد";
			break;
	}	
	
       return  $pay_error;
}
/************************************************* r e s   e r r o r ***********************************************/
function check_saman_res_error($res){
	switch($res){
		case '1' :
			$prompt="فرايند بازگشت با موفقيت انجام شد";
			break;
		case '-1' :
			$prompt="خطاي داخلي شبکه مالي.";
			break;
		case '-2' :
			$prompt="سپرده‌ها برابر نيستند. ( در حال حاضر اين شرايط به وجود نمي آيد)";
			break;
		case '-3' :
			$prompt="ورودي‌ها حاوي کارکترهاي غيرمجاز مي‌باشند.";
			break;
		case '-4' :
			$prompt="Merchant Authentication Failed ( کلمه عبور يا کد فروشنده اشتباه است)";
			break;
		case '-5' :
			$prompt="Database Exception";
			break;
		case '-6' :
			$prompt="سند قبلا برگشت کامل يافته است.";
			break;
		case '-7' :
			$prompt="رسيد ديجيتالي تهي است.";
			break;
		case '-8' :
			$prompt="طول ورودي‌ها بيشتر از حد مجاز است.";
			break;
		case '-9' :
			$prompt="وجود کارکترهاي غيرمجاز در مبلغ برگشتي.";
			break;
		case '-10' :
			$prompt="رسيد ديجيتالي به صورت Base64 نيست (حاوي کارکترهاي غيرمجاز است).";
			break;
		case '-11' :
			$prompt="طول ورودي‌ها کمتر از حد مجاز است.";
			break;
		case '-12' :
			$prompt="مبلغ برگشتي منفي است.";
			break;
		case '-13' :
			$prompt="مبلغ برگشتي براي برگشت جزئي بيش از مبلغ برگشت نخورده‌ي رسيد ديجيتالي است.";
			break;
		case '-14' :
			$prompt="چنين تراکنشي تعريف نشده است.";
			break;
		case '-15' :
			$prompt="مبلغ برگشتي به صورت اعشاري داده شده است.";
			break;
		case '-16' :
			$prompt="خطاي داخلي سيستم";
			break;
		case '-17' :
			$prompt="برگشت زدن جزيي تراکنشي که با کارت بانکي غير از بانک سامان انجام پذيرفته است.";
			break;
		case '-18' :
			$prompt="IP Address  فروشنده نا معتبر است.";
			break;
		DEFAULT :
			$prompt="Invalid error state : $res";
			break;
	}
	return  $prompt;
}	  
?>