<?php
/*
  $Id: ssl_check.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Info_Ssl_check extends osC_Template {

/* Private variables */

    var $_module = 'ssl_check',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'ssl_check.php',
        $_page_image = 'table_background_specials.gif';

/* Class constructor */

    function osC_Info_Ssl_check() {
      global $osC_Services, $osC_Language, $breadcrumb;

      $this->_page_title = $osC_Language->get('info_ssl_check_heading');

      if ($osC_Services->isStarted('breadcrumb')) {
        $breadcrumb->add($osC_Language->get('breadcrumb_ssl_check'), osc_href_link(FILENAME_INFO, $this->_module));
      }
    }
  }
?>
