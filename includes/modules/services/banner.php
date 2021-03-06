<?php
/*
  $Id: banner.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_banner {
    function start() {
      global $osC_Banner;

      require('includes/classes/banner.php');
      $osC_Banner = new osC_Banner();

      $osC_Banner->activateAll();
      $osC_Banner->expireAll();

      return true;
    }

    function stop() {
      return true;
    }
  }
?>
