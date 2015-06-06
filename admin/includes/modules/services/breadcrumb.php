<?php
/*
  $Id: breadcrumb.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_breadcrumb_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends,
        $precedes;

    function osC_Services_breadcrumb_Admin() {
      global $osC_Language;

      $osC_Language->loadIniFile('modules/services/breadcrumb.php');

      $this->title = $osC_Language->get('services_breadcrumb_title');
      $this->description = $osC_Language->get('services_breadcrumb_description');
    }

    function install() {
      return false;
    }

    function remove() {
      return false;
    }

    function keys() {
      return false;
    }
  }
?>
