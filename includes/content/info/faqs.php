<?php
/*
  $Id: faqs.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
  require_once('includes/classes/faqs.php');

  class osC_Info_Faqs extends osC_Template {

/* Private variables */

    var $_module = 'faqs',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'faqs.php',
        $_page_image = 'table_background_account.gif';

    function osC_Info_Faqs() {
      global $osC_Language;

      $this->_page_title = $osC_Language->get('info_faqs_heading');
    }
  }
?>
