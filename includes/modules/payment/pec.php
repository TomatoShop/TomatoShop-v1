<?php
###############################################################################
###############################################################################
##                                                                           ##
##  $Id: pec.php $                                                           ##
##  PARSIAN BANK PAYMENT FOR PERSIAN TOMATOCART                              ##
##                                                                           ##
##  HTTP://WWW.TOMATOSHOP.IR                                                 ##
##  Copyright (c) 2011 TOMATOSHOP.IR                                         ##
##  AUTHOR Ali Masooumi ( masooumi[at]gmail[dot]com )                        ##
##  PEC VERSION 0.1  2011/08/25                                              ##
##                                                                           ##
###############################################################################
###############################################################################
require_once ('ext/lib/nusoap.php');
class osC_Payment_pec extends osC_Payment {
	var $_title, $_code = 'pec', $_status = false, $_sort_order, $_order_id;
	function osC_Payment_pec() {
		global $osC_Database, $osC_Language, $osC_ShoppingCart;
		$this->_title = $osC_Language->get('payment_pec_title');
		$this->_method_title = $osC_Language->get('payment_pec_method_title');
		$this->_status = (MODULE_PAYMENT_PEC_STATUS == '1') ? true : false;
		$this->_sort_order = MODULE_PAYMENT_PEC_SORT_ORDER;
		$this->form_action_url = 'https://www.pec24.com/pecpaymentgateway/default.aspx';
		if ($this->_status === true) {
			if ((int) MODULE_PAYMENT_PEC_ORDER_STATUS_ID > 0) {
				$this->order_status = MODULE_PAYMENT_PEC_ORDER_STATUS_ID;
			}
			if ((int) MODULE_PAYMENT_PEC_ZONE > 0) {
				$check_flag = false;
				$Qcheck = $osC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
				$Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
				$Qcheck->bindInt(':geo_zone_id', MODULE_PAYMENT_PEC_ZONE);
				$Qcheck->bindInt(':zone_country_id', $osC_ShoppingCart->getBillingAddress('country_id'));
				$Qcheck->execute();
				while ($Qcheck->next()) {
					if ($Qcheck->valueInt('zone_id') < 1) {
						$check_flag = true;
						break;
					}
					elseif ($Qcheck->valueInt('zone_id') == $osC_ShoppingCart->getBillingAddress('zone_id')) {
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
		return array('id' => $this->_code, 'module' => $this->_method_title);
	}
	function pre_confirmation_check() {
		return false;
	}
	function confirmation() {
		global $osC_Language, $osC_CreditCard;
		$this->_order_id = osC_Order :: insert(ORDERS_STATUS_PREPARING);
		$confirmation = array('title' => $this->_method_title, 'fields' => array(array('title' => $osC_Language->get('payment_pec_description'))));
		return $confirmation;
	}
	function process_button() {
		global $osC_Currencies, $osC_ShoppingCart, $osC_Language, $osC_Database;
		if (MODULE_PAYMENT_PEC_CURRENCY == 'Selected Currency') {
			$currency = $osC_Currencies->getCode();
		}
		else {
			$currency = MODULE_PAYMENT_PEC_CURRENCY;
		}
		$amount = round($osC_Currencies->formatRaw($osC_ShoppingCart->getTotal(), $currency), 2);
		$order = $this->_order_id;
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		//$page = curl_exec ($ch);
		if (MODULE_PAYMENT_PEC_GATEWAY == 'pec-shaparak') {
		$client = new nusoap_client('https://pec.shaparak.ir/pecpaymentgateway/eshopservice.asmx?wsdl', 'wsdl');
		} elseif(MODULE_PAYMENT_PEC_GATEWAY == 'pec24') {
		$client = new nusoap_client('https://www.pec24.com/pecpaymentgateway/eshopservice.asmx?wsdl', 'wsdl');
		} else {
		$client = new nusoap_client('https://www.pecco24.com:27635/pecpaymentgateway/eshopservice.asmx?wsdl', 'wsdl');
		}
		///////////////// PIN PAY REQUEST
		$amount = $amount;
		// here is the posted amount
		$orderId = $order;
		$authority = 0;
		// default authority
		$status = 1;
		// default status
		$callbackUrl = osc_href_link(FILENAME_CHECKOUT, 'process', 'SSL', null, null, true);
		// Check for an error
		$err = $client->getError();
		if ($err) {
			echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
			die();
		}
		$parameters = array(
                         'pin' => MODULE_PAYMENT_PEC_PIN,  		// this is our PIN NUMBER
	                   	 'amount' => $amount,
                         'orderId' => $orderId,
                         'callbackUrl' => $callbackUrl,
                         'authority' => $authority,
                         'status' => $status);
		// Call the SOAP method
		$result = $client->call('PinPaymentRequest', $parameters);
		// Check for a fault
		if ($client->fault) {
			echo '<h2>Fault</h2><pre>';
			print_r($result);
			echo '</pre>';
			die();
		}
		else {
			// Check for errors
			$resultStr = $result;
			$err = $client->getError();
			if ($err) {
				// Display the error
				echo '<h2>Error</h2><pre>' . $err . '</pre>';
				die();
			}
			else {
				// Display the result
				//$res = explode (',',$resultStr);
				//echo "<script>alert('Pay Response is : " . $resultStr . "');</script>";
				// echo "Pay Response is : " . $resultStr; //show resultStr in payment page
				$authority = $resultStr['authority'];
				$status = $resultStr['status'];
				if (($authority) and ($status == '0')) {
					// Update table, Save RefId
					//echo "<script language='javascript' type='text/javascript'>postRefId('" . $res[1] . "');</script>";
					// insert ref id in database
					$osC_Database->simpleQuery("insert into `" . DB_TABLE_PREFIX . "online_transactions`
					  		(orders_id,receipt_id,transaction_method,transaction_date,transaction_amount,transaction_id) values
		                    ('$order','$authority','pec','','$amount','')
					  ");
					//
					if (MODULE_PAYMENT_PEC_GATEWAY == 'pec-shaparak') {
					echo '<div style="text-align:left;">' . osc_link_object(osc_href_link('https://pec.shaparak.ir/pecpaymentgateway/default.aspx?au=' . $authority, '', '', '', false), osc_draw_image_button('button_confirm_order.gif', $osC_Language->get('button_confirm_order'), 'id="btnConfirmOrder"')) . '</div>';				
					} elseif(MODULE_PAYMENT_PEC_GATEWAY == 'pec24') {
					echo '<div style="text-align:left;">' . osc_link_object(osc_href_link('https://www.pec24.com/pecpaymentgateway/default.aspx?au=' . $authority, '', '', '', false), osc_draw_image_button('button_confirm_order.gif', $osC_Language->get('button_confirm_order'), 'id="btnConfirmOrder"')) . '</div>';
					} else {
					echo '<div style="text-align:left;">' . osc_link_object(osc_href_link('https://www.pecco24.com:27635/pecpaymentgateway/?au=' . $authority, '', '', '', false), osc_draw_image_button('button_confirm_order.gif', $osC_Language->get('button_confirm_order'), 'id="btnConfirmOrder"')) . '</div>';
					}
					//  echo osc_redirect(osc_href_link('https://pec.shaparak.ir/pecpaymentgateway/default.aspx?au=' . $authority,'','','',false));
				}
				else {
					// log error in app
					// Update table, log the error
					osC_Order :: remove($this->_order_id);
					echo '<div style="font-size:11px; color:#cc0000; width:500; border:1px solid #cc0000; padding:5px; background:#ffffcc;">' . check_pec_state_error($status) . '</div>';
				}
			}
			// end Display the result
		}
		// end Check for errors
		//   $process_button_string .= osc_draw_hidden_field('au', $authority);
		//  return $process_button_string;
	}
	function get_error() {
		global $osC_Language;
		return $error;
	}
	function process() {
		global $osC_Language, $osC_Customer, $osC_ShoppingCart, $_POST, $_GET, $messageStack, $osC_Database;
		$authority = $_REQUEST['au'];
		$status = $_REQUEST['rs'];
		$this->_order_id = osC_Order :: insert(ORDERS_STATUS_PREPARING);
		$order = $this->_order_id;
		// order id for reversal
		if ($authority) {
			// here we update our database
			if (($status == '0')				/* and (checkDataBase(...))*/
				) {
				//curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				//$page = curl_exec ($ch);
				if (MODULE_PAYMENT_PEC_GATEWAY == 'pec-shaparak') {
				$client = new nusoap_client('https://pec.shaparak.ir/pecpaymentgateway/eshopservice.asmx?wsdl', 'wsdl');
				} elseif(MODULE_PAYMENT_PEC_GATEWAY == 'pec24') {
				$client = new nusoap_client('https://www.pec24.com/pecpaymentgateway/eshopservice.asmx?wsdl', 'wsdl');
				} else {
				$client = new nusoap_client('https://www.pecco24.com:27635/pecpaymentgateway/eshopservice.asmx?wsdl', 'wsdl');
				}			
				///////////////// INQUIRY REQUEST
				$inquiryauthority = $authority;
				$inquirystatus = 1;
				// default status
				// Check for an error
				$err = $client->getError();
				if ($err) {
					echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
					die();
				}
				$parameters = array(
                                'pin' => MODULE_PAYMENT_PEC_PIN,
                                'authority' => $inquiryauthority,
                                'status' => $inquirystatus);
				// Call the SOAP method
				$result = $client->call('PinPaymentEnquiry', $parameters);
				// Check for a fault
				if ($client->fault) {
					echo '<h2>Fault1</h2><pre>';
					print_r($result);
					echo '</pre>';
					die();
				}
				else {
					$resultStr = $result;
					$status = $resultStr['status'];
					if ($status == '0') {
						//$status==0 --> $status==a2l2i for test
						// this is a succcessfull payment
						// we update our DataBase
						//  save transaction_id to database
						$osC_Database->simpleQuery("update `" . DB_TABLE_PREFIX . "online_transactions` set transaction_id = '$authority',transaction_date = '" . date("YmdHis") . "' where 1 and ( receipt_id = '$authority' )");
						//
						$Qtransaction = $osC_Database->query('insert into :table_orders_transactions_history (orders_id, transaction_code, transaction_return_value, transaction_return_status, date_added) values (:orders_id, :transaction_code, :transaction_return_value, :transaction_return_status, now())');
						$Qtransaction->bindTable(':table_orders_transactions_history', TABLE_ORDERS_TRANSACTIONS_HISTORY);
						$Qtransaction->bindInt(':orders_id', $order);
						$Qtransaction->bindInt(':transaction_code', 1);
						$Qtransaction->bindValue(':transaction_return_value', $authority);
						$Qtransaction->bindInt(':transaction_return_status', 1);
						$Qtransaction->execute();
						//
						$this->_order_id = osC_Order :: insert();
						$comments = $osC_Language->get('payment_pec_method_authority') . '[' . $authority . ']';
						osC_Order :: process($this->_order_id, $this->order_status, $comments);
					}
					else {
						$err = $client->getError();
						if ($err) {
							//$err --> $err='ali' for test
							///////////////// REVERSAL REQUEST
							$order = $this->_order_id;
							// order id for reversal
							$orderid = $order;
							$reversaltoreversal = $order;
							$reversalstatus = 1;
							// default status
							// Check for an error
							$err = $client->getError();
							if ($err) {
								echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
								die();
							}
							$parameters = array(
                                            'pin' => MODULE_PAYMENT_PEC_PIN,
                                            'orderId' => $orderid,
                                            'orderToReversal' => $reversaltoreversal,
                                            'status' => $reversalstatus);
							// Call the SOAP method
							$result = $client->call('PinReversal', $parameters);
							// Check for a fault
							if ($client->fault) {
								echo '<h2>Fault3</h2><pre>';
								print_r($result);
								echo '</pre>';
								die();
							}
							else {
								$resultStr = $result;
								$err = $client->getError();
								if ($err) {
									// Display the error
									echo '<h2>Error</h2><pre>' . $err . '</pre>';
									die();
								}
								else {
									// Update Table, Save Reversal Status
									// Note: Successful Reversal means that sale is reversed.
									//echo "<script>alert('Reversal Response is : " . $resultStr . "');</script>";
									//	echo "Reversal Response is : " . $resultStr;
									//  delete receipt id from database
									$osC_Database->simpleQuery("delete from `" . DB_TABLE_PREFIX . "online_transactions` where 1 and ( receipt_id = '$authority' ) and ( orders_id = '$order' )");
									//
									osC_Order :: remove($this->_order_id);
									$messageStack->add_session('checkout', 'خطا در تایید تراکنش ، مبلغ تراکنش با موفقیت به حساب شما برگشت داده شد.', 'error');
									osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true));
									//
								}
								// end Display the result
							}
							// end Check for errors
						}
						//  delete receipt id from database
						$osC_Database->simpleQuery("delete from `" . DB_TABLE_PREFIX . "online_transactions` where 1 and ( receipt_id = '$authority' ) and ( orders_id = '$order' )");
						//
						osC_Order :: remove($this->_order_id);
						$messageStack->add_session('checkout', check_pec_state_error($status), 'error');
						osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true));
						//
					}
				}
			}
			else {
				//  delete receipt id from database
				$osC_Database->simpleQuery("delete from `" . DB_TABLE_PREFIX . "online_transactions` where 1 and ( receipt_id = '$authority' ) and ( orders_id = '$order' )");
				//
				// this is a UNsucccessfull payment
				osC_Order :: remove($this->_order_id);
				$messageStack->add_session('checkout', check_pec_state_error($status), 'error');
				osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true));
			}
		}
		else {
			//  delete receipt id from database
			$osC_Database->simpleQuery("delete from `" . DB_TABLE_PREFIX . "online_transactions` where 1 and ( orders_id = '$order' )");
			//
			// this is a UNsucccessfull payment
			osC_Order :: remove($this->_order_id);
			$messageStack->add_session('checkout', check_pec_state_error($status), 'error');
			osc_redirect(osc_href_link(FILENAME_CHECKOUT, 'checkout&view=paymentInformationForm', 'SSL', null, null, true));
		}
	}
	function callback() {
		global $osC_Database;
		//
	}
}
##-----------------------------------------------------------------------------____________________CHECK_PEC_STATE_ERROR
function check_pec_state_error($status) {
	switch ($status) {
		case '0' :
			$pay_error = "عمليات موفقيت آميز بود.";
			break;
		case '1' :
			$pay_error = "عمليات پرداخت پول لغو شده است.";
			break;
		case '2' :
			$pay_error = "مشکل Timeout در تراکنش به وجود آمده است.";
			break;
		case '10' :
			$pay_error = "شماره کارت وارد شده نامعتبر است.";
			break;
		case '11' :
			$pay_error = "تاريخ انقضاي کارت نامعتبر است يا تاريخ اعتبار کارت تمام شده است.";
			break;
		case '12' :
			$pay_error = "کد pin نامعتبر است.";
			break;
		case '13' :
			$pay_error = "محدوده مبلغ نامعتبر است يا از حدمجاز براي مشتري بيشتر است.";
			break;
		case '14' :
			$pay_error = "مقدار مبلغ از حداکثر مجاز براي فروشنده بيشتر است.";
			break;
		case '15' :
			$pay_error = "مقدار مبلغ از حداکثر مبغ مجاز براي برداشت در يکروز بيشتر است.";
			break;
		case '16' :
			$pay_error = "pin مربوط به کار غيرمعتبر است.";
			break;
		case '17' :
			$pay_error = "کارتي که براي پرداخت استفاده شده است مربوط به بانک پارسيان نيست.";
			break;
		case '18' :
			$pay_error = "درخواست تراکنش نامعتبر است.";
			break;
		case '20' :
			$pay_error = "شماره پین کد فروشنده نادرست است!";
			break;
		case '21' :
			$pay_error = "کد Authority نامعتبر است.";
			break;
		case '22' :
			$pay_error = "شماره پین کد فروشنده نادرست است!";
			break;
		case '30' :
			$pay_error = "عمليات قبلا با موفقيت انجام شده است.";
			break;
		case '31' :
			$pay_error = "عمليات با موفقيت لغو شده است.";
			break;
		case '32' :
			$pay_error = "عمليات با موفقيت برگشت خورده است.";
			break;
		case '33' :
			$pay_error = "مشتري اطلاعات مربوط به کارت خود را به تعداد دفعات مجاز اشتباه وارد کرده است.";
			break;
		case '34' :
			$pay_error = "شماره تراکنش فروشنده صحيح نيست.";
			break;
		case '35' :
			$pay_error = "در عمليات ناسازگاري وجود دارد.";
			break;
		case '36' :
			$pay_error = "عمليات با شماره تراکنش ارسال شده قبلا با موفقيت لغو شده است.";
			break;
		case '37' :
			$pay_error = "تراکنش با شماره تراکنش ارسال شده قبلا با موفقيت برگشت خورده است.";
			break;
		case '38' :
			$pay_error = "مبلغ درخواستي براي برگشت زدن از کل مبلغ سفارش بيشتر است.";
			break;
		case '39' :
			$pay_error = "مبلغ درخواستي براي برگشت زدن از تعداد مبالغ سفارشات بيشتر است.";
			break;
		case '40' :
			$pay_error = "درخواست برگشت زدن مبلغ نامعتبر است.";
			break;
		case '42' :
			$pay_error = "درخواست پرداخت مشتري در حال انجام مي باشد.";
			break;
		case '50' :
			$pay_error = "تراکنش در ديتابيس ثبت شده است و آماده اجرا مي باشد.";
			break;
		case '51' :
			$pay_error = "تراکنش با موفقيت دريافت شده است.";
			break;
		case ' 52' :
			$pay_error = "درخواست تراکنش در حال انجام مي باشد.";
			break;
		case '53' :
			$pay_error = "تراکنش در حال بررسي توسط فروشنده مي باشد.";
			break;
		case '54' :
			$pay_error = "اطلاعات کارت دريافت شده است.";
			break;
		case '60' :
			$pay_error = "پاسخي از طرف بانک دريافت نشد.";
			break;
		case '61' :
			$pay_error = "ارسال درخواست به بانک ناموفق بود.";
			break;
		case '62' :
			$pay_error = "فروشنده وارد نشده است.";
			break;
		case '63' :
			$pay_error = "قالب بندي داراي اشکال مي باشد.";
			break;
		case '64' :
			$pay_error = "کارتخوان نامعتبر است.";
			break;
		case '65' :
			$pay_error = "کدهاي محصول غيرمعتبر است.";
			break;
		case '66' :
			$pay_error = "عمليات نامعتبر است.";
			break;
		case '67' :
			$pay_error = "خطاي تطبيق به وجود آمده است.";
			break;
		case '68' :
			$pay_error = "رکورد اطلاعات پيدا نشد.";
			break;
		case '69' :
			$pay_error = "تراکنش دوباره وارد شده است.";
			break;
		case '70' :
			$pay_error = "مشکل رجوع به وجود آمده است.";
			break;
		case '71' :
			$pay_error = "مشکل داخلي در سيستم به وجود آمده است.";
			break;
		case '72' :
			$pay_error = "مشکل داخلي در سيستم به وجود آمده است.";
			break;
		case '73' :
			$pay_error = "شماره تراکنش پيدا نشد.";
			break;
		case '74' :
			$pay_error = "بنا به قوانين تراکنش قابل انجام نيست.";
			break;
		case '75' :
			$pay_error = "کد ترمينال درست نيست.";
			break;
		case '76' :
			$pay_error = "بانک موردنظر شما امکان پرداخت اينترنتي ندارد.";
			break;
		case '77' :
			$pay_error = "شماره دسته يافت نشد.";
			break;
		case '78' :
			$pay_error = "درخواست دوبار ارسال شده است.";
			break;
		case '79' :
			$pay_error = "تراکنش ناموفق بود.";
			break;
		case '80' :
			$pay_error = "خطاي نامعلوم رخ داده است.";
			break;
		case '81' :
			$pay_error = "در ارتباط شبکه اي مشکلي به وجود آمده است.";
			break;
		case '90' :
			$pay_error = "خطاي داخل سيستم بوجود آمده است.";
			break;
		case '91' :
			$pay_error = "تراکنش به دليل مشکل در ديتابيس ناموفق بود.";
			break;
		DEFAULT :
			$pay_error = "خطاي نامشخص [خطاي شماره : $status]";
	}
	return '<b>خطا شماره ' . $status . ' :</b> ' . $pay_error;
}
?>