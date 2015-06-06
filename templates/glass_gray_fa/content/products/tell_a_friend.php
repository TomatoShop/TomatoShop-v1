<?php
/*
  $Id: tell_a_friend.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>

<div style="float: left;"><?php echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getID()), $osC_Image->show($osC_Product->getImage(), $osC_Product->getTitle(), 'hspace="5" vspace="5"', 'mini')); ?></div>

<h1><?php echo $osC_Template->getPageTitle() . ($osC_Product->hasSKU() ? '<br /><span class="smallText">' . $osC_Product->getSKU() . '</span>' : ''); ?></h1>

<div style="clear: both"></div>

<?php
  if ($messageStack->size('tell_a_friend') > 0) {
    echo $messageStack->output('tell_a_friend');
  }
?>

<form name="tell_a_friend" action="<?php echo osc_href_link(FILENAME_PRODUCTS, 'tell_a_friend&' . $osC_Product->getID() . '&action=process'); ?>" method="post">

<div class="moduleBox">
  <h6><em style="float: left;"><?php echo $osC_Language->get('form_required_information'); ?></em><?php echo $osC_Language->get('customer_details_title'); ?></h6>

  <div class="content">
    <ol>
      <li><?php echo osc_draw_label($osC_Language->get('field_tell_a_friend_customer_name'), null, 'from_name', true) . osc_draw_input_field('from_name', ($osC_Customer->isLoggedOn() ? $osC_Customer->getName() : null)); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('field_tell_a_friend_customer_email_address'), null, 'from_email_address', true) . osc_draw_input_field('from_email_address', ($osC_Customer->isLoggedOn() ? $osC_Customer->getEmailAddress() : null), 'dir="ltr"'); ?></li>
    </ol>
  </div>
</div>

<div class="moduleBox">
  <h6><?php echo $osC_Language->get('friend_details_title'); ?></h6>

  <div class="content">
    <ol>
      <li><?php echo osc_draw_label($osC_Language->get('field_tell_a_friend_friends_name'), null, 'to_name', true) . osc_draw_input_field('to_name'); ?></li>
      <li><?php echo osc_draw_label($osC_Language->get('field_tell_a_friend_friends_email_address'), null, 'to_email_address', true) . osc_draw_input_field('to_email_address', null, 'dir="ltr"'); ?></li>
    </ol>
  </div>
</div>

<div class="moduleBox">
  <h6><?php echo $osC_Language->get('tell_a_friend_message'); ?></h6>

  <div class="content">
    <ol>
      <li><?php echo osc_draw_textarea_field('message', null, 40, 8, 'style="width: 98%;"'); ?></li>
    </ol>
  </div>
</div>

<div class="submitFormButtons">
  <span style="float: left;"><?php echo osc_draw_image_submit_button('button_continue.gif', $osC_Language->get('button_continue')); ?></span>

  <?php echo osc_link_object(osc_href_link(FILENAME_PRODUCTS, $osC_Product->getID()), osc_draw_image_button('button_back.gif', $osC_Language->get('button_back'))); ?>
</div>

</form>
