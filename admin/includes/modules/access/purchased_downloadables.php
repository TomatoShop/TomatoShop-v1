<?php
/*
  $Id: purchased_downloadables.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Access_Purchased_Downloadables extends osC_Access {
    var $_module = 'purchased_downloadables',
        $_group = 'customers',
        $_icon = 'page.png',
        $_title,
        $_sort_order = 700;

    function osC_Access_Purchased_Downloadables() {
      global $osC_Language;

      $this->_title = $osC_Language->get('access_purchased_downloadables_title');
    }
  }
?>
