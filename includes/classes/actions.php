<?php
/*
  $Id: actions.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Actions {
    function parse() {
      if (isset($_GET['action']) && !empty($_GET['action'])) {
        $_GET['action'] = basename($_GET['action']);

        if (file_exists('includes/modules/actions/' . $_GET['action'] . '.php')) {
          include('includes/modules/actions/' . $_GET['action'] . '.php');

          call_user_func(array('osC_Actions_' . $_GET['action'], 'execute'));
        }
      }
    }
  }
?>
