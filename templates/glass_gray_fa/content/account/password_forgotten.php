<?php
/*
  $Id: password_forgotten.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<?php
  if ($messageStack->size('password_forgotten') > 0) {
    echo $messageStack->output('password_forgotten');
  }
?>

<form name="password_forgotten" action="<?php echo osc_href_link(FILENAME_ACCOUNT, 'password_forgotten=process', 'SSL'); ?>" method="post" onsubmit="return check_form(password_forgotten);">

<div class="moduleBox">
  <h6><?php echo $osC_Language->get('password_forgotten_heading'); ?></h6>

  <div class="content">
    <p><?php echo $osC_Language->get('password_forgotten'); ?></p>

    <ol>
      <li><?php echo osc_draw_label($osC_Language->get('field_customer_email_address'), 'email_address') . osc_draw_input_field('email_address',NULL,'dir="ltr"'); ?></li>
    </ol>
  </div>
</div>

<div class="submitFormButtons">
  <span style="float: left;"><?php echo osc_draw_image_submit_button('button_continue.gif', $osC_Language->get('button_continue')); ?></span>

  <?php echo osc_link_object(osc_href_link(FILENAME_ACCOUNT, null, 'SSL'), osc_draw_image_button('button_back.gif', $osC_Language->get('button_back'))); ?>
</div>

</form>
