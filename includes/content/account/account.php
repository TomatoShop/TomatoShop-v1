<?php
/*
  $Id: account.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  require('includes/classes/order.php');

  class osC_Account_Account extends osC_Template {

/* Private variables */

    var $_module = 'account',
        $_group = 'account',
        $_page_title,
        $_page_contents = 'account.php',
        $_page_image = 'table_background_account.gif';

    function osC_Account_Account() {
      global $osC_Language;

      $this->_page_title = $osC_Language->get('account_heading');
    }
  }
?>
