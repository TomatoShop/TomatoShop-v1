<?php
/*
  $Id: bank_receipt.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

 $ordernumber =array();
 
 $Qlisting = toC_BankReceipt::getOrderList();
 
 while($Qlisting->next()) {
   $ordernumber[] = array('id' => $Qlisting->value('orders_id'),
                         'text' => $Qlisting->value('orders_id'));
   
   $order_date_purchased[$Qlisting->value('orders_id')] = $Qlisting->value('date_purchased');
 }
 
  if (isset($_GET['orders_id'])) {
 $ordernumberdefault = $_GET['orders_id'];
 }  
?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<?php
  if ($messageStack->size('bank_receipt') > 0) {
    echo $messageStack->output('bank_receipt');
  }

  if (isset($_GET['bank_receipt']) && ($_GET['bank_receipt'] == 'success')) {
?>

<p><?php echo $osC_Language->get('bank_receipt_sent_successfully'); ?></p>

<div class="submitFormButtons" style="text-align: left;">
  <?php echo osc_link_object(osc_href_link(FILENAME_INFO, 'bank_receipt'), osc_draw_image_button('button_continue.gif', $osC_Language->get('button_continue'))); ?>
</div>

<?php
  } else {
?>

<div class="moduleBox">
  <h6><?php echo $osC_Language->get('bank_receipt_title'); ?></h6>

  <div class="content">

    <p style="margin-top: 0px;"><?php echo $osC_Language->get('bank_receipt_please_login_for_receipt_register'); ?></p>

    <div style="clear: both;"></div>
  </div>
</div>

<form name="contact" action="<?php echo osc_href_link(FILENAME_INFO, 'bank_receipt=process', 'AUTO', true, false); ?>" method="post">

<div class="moduleBox">
  <h6></h6>
  <div class="content contact">
    <ol>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_name_title'), 'name', null, true) . osc_draw_input_field('name', $osC_Customer->getName(), 'size="30"'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_telephone_title'), 'telephone') . osc_draw_input_field('telephone', '', 'size="30"dir="ltr"'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_email_address_title'), 'email', null, true) . osc_draw_input_field('email', $osC_Customer->getEmailAddress(), 'size="30"dir="ltr"'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_amount_title'), 'amount', null, true) . osc_draw_input_field('amount', '', 'size="30"dir="ltr"'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_bank_name_title'), 'bankname', null, true) . osc_draw_input_field('bankname', '', 'size="30"'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_receipt_number_title'), 'receiptnumber', null, true) . osc_draw_input_field('receiptnumber', '', 'size="30" dir="ltr"'); ?></li>
	  <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_receipt_date_title'), 'receiptdate', null, true) . osc_draw_jdate_pull_down_menu('receiptdate', null, true, null, null, 1, 0); ?></li>
    <?php if ($osC_Customer->isLoggedOn() === true){ ?>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_customer_order_number_title'), 'ordernumber') . osc_draw_pull_down_menu('ordernumber', $ordernumber, $ordernumberdefault); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_order_date_title'), 'orderdate'); ?></li><span id="order_date_purchased"></span>
    <?php } else { ?>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_order_number_title'), 'ordernumber', null, true) . osc_draw_input_field('ordernumber', '', 'size="30"dir="ltr"'); ?></li>
    <?php } ?>
      <li><?php echo osc_draw_label($osC_Language->get('bank_receipt_description_title'), 'description') . osc_draw_textarea_field('description', null, 38, 5); ?></li>

    <?php if( ACTIVATE_CAPTCHA == '1') {?>
      <li class="clearfix captcha" style="height:130px !important;">
        <table width="200" border="0">
          <tr>
            <td>&nbsp;</td>
            <td><?php echo osc_image(osc_href_link(FILENAME_INFO, 'bank_receipt=show_captcha', 'AUTO', true, false), $osC_Language->get('captcha_image_title'), 215, 80, 'id="captcha-code"'); ?></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><?php echo osc_draw_label($osC_Language->get('enter_captcha_code'), 'captcha_code', null, true)?></td>
            <td><?php echo osc_draw_input_field('captcha_code', '', 'size="30"dir="ltr"')?></td>
            <td><?php echo osc_link_object(osc_href_link('#'), osc_image('ext/securimage/images/refresh.png',$osC_Language->get('refresh_captcha_image_title')), 'id="refresh-captcha-code"');?></td>
          </tr>
        </table>
      </li>
    <?php } ?>
    </ol>
  </div>
</div>

<?php
  echo osc_draw_hidden_session_id_field();
?>

<div class="submitFormButtons" style="text-align: left;">
  <?php echo osc_draw_image_submit_button('button_continue.gif', $osC_Language->get('button_continue')); ?>
</div>

</form>
<?php if( ACTIVATE_CAPTCHA == '1') {?>
<script type="text/javascript">
  $('refresh-captcha-code').addEvent('click', function(e) {
    e.stop();
    
    var contactController = '<?php echo osc_href_link(FILENAME_INFO, 'bank_receipt=show_captcha', 'AUTO', true, false); ?>';
    var captchaImgSrc = contactController + '&' + Math.random();
          
    $('captcha-code').setProperty('src', captchaImgSrc);
  });
</script>
<?php } ?>
  
  <?php if (!empty($order_date_purchased)) { ?>
    <script type="text/javascript">
      window.addEvent("domready", function() {
        var datepurchased = {};
      <?php
        foreach($order_date_purchased as $key => $datepurchased) {
      ?>
      
        datepurchased['<?php echo $key; ?>'] = '<?php echo osC_DateTime::getLong($datepurchased); ?>';
      
      <?php } ?>
          
        $('order_date_purchased').set('html', datepurchased[$('ordernumber').get('value')]);
          
        $('ordernumber').addEvent('change', function() {
          $('order_date_purchased').set('html', datepurchased[this.value]);
        });
      });
    
    </script>
<?php
    }
  }
?>