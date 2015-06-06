<?php
/*
  $Id: cookie.php $
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

<div class="moduleBox" style="width: 40%; float: left; margin: 0 10px 10px 0;">
  <h6><?php echo $osC_Language->get('cookie_usage_box_heading'); ?></h6>

  <div class="content">
    <?php echo $osC_Language->get('cookie_usage_box_contents'); ?>
  </div>
</div>

<p><?php echo $osC_Language->get('cookie_usage'); ?></p>

<div class="submitFormButtons" style="text-align: left;">
  <?php echo osc_link_object(osc_href_link(FILENAME_INFO), osc_draw_image_button('button_continue.gif', $osC_Language->get('button_continue'))); ?>
</div>
