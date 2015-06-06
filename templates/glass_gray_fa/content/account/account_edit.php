<?php
/*
  $Id: account_edit.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  $Qaccount = osC_Account::getEntry();
?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<?php
  if ($messageStack->size('account_edit') > 0) {
    echo $messageStack->output('account_edit');
  }
?>

<form name="account_edit" action="<?php echo osc_href_link(FILENAME_ACCOUNT, 'edit=save', 'SSL'); ?>" method="post" onsubmit="return check_form(account_edit);">

<div class="moduleBox">

  <h6><em><?php echo $osC_Language->get('form_required_information'); ?></em><?php echo $osC_Language->get('my_account_title'); ?></h6>

  <div class="content">
    <ol>

<?php
  if (ACCOUNT_GENDER > -1) {
    $gender_array = array(array('id' => 'm', 'text' => $osC_Language->get('gender_male')),
                          array('id' => 'f', 'text' => $osC_Language->get('gender_female')));
?>

      <li><?php echo osc_draw_label($osC_Language->get('field_customer_gender'), 'gender1', null, (ACCOUNT_GENDER > 0)) . osc_draw_radio_field('gender', $gender_array, $Qaccount->value('customers_gender')); ?></li>

<?php
  }
?>

      <li><?php echo osc_draw_label($osC_Language->get('field_customer_first_name'), 'firstname', null, true) . ' ' . osc_draw_input_field('firstname', $Qaccount->value('customers_firstname')); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_last_name'), 'lastname', null, true) . ' ' . osc_draw_input_field('lastname', $Qaccount->value('customers_lastname')); ?></li>

<?php
  if (ACCOUNT_DATE_OF_BIRTH == '1') {
	  $g_t_j = gregorian_to_jalali($Qaccount->value('customers_dob_year'), $Qaccount->value('customers_dob_month'), $Qaccount->value('customers_dob_date'));
?>

      <li><?php echo osc_draw_label($osC_Language->get('field_customer_date_of_birth'), 'dob_days', null, true) . ' ' . osc_draw_jdate_pull_down_menu('dob', array('year' => $g_t_j['0'], 'month' => $g_t_j['1'], 'date' => $g_t_j['2']), false, null, null, date('Y')-1921, -5); ?></li>

<?php
  }
?>

      <li><?php echo osc_draw_label($osC_Language->get('field_customer_email_address'), 'email_address', null, true) . ' ' . osc_draw_input_field('email_address', $Qaccount->value('customers_email_address'),'dir="ltr"'); ?></li>
      
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_group'), null, null, false) . ' ' . $Qaccount->value('customers_groups_name'); ?></li>
      
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_store_credit'), null, null, false) . ' ' . $osC_Currencies->format($Qaccount->value('customers_credits')); ?></li>
    </ol>
  </div>
</div>

<div class="submitFormButtons" style="text-align: left;">
  <?php echo osc_draw_image_submit_button('button_continue.gif', $osC_Language->get('button_continue')); ?>
</div>

</form>
