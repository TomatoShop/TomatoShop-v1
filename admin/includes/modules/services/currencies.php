<?php
/*
  $Id: currencies.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_currencies_Admin {
    var $title,
        $description,
        $uninstallable = false,
        $depends = 'language',
        $precedes;

    function osC_Services_currencies_Admin() {
      global $osC_Language;

      $osC_Language->loadIniFile('modules/services/currencies.php');

      $this->title = $osC_Language->get('services_currencies_title');
      $this->description = $osC_Language->get('services_currencies_description');
    }

    function install() {
      global $osC_Database;

      $description = '<p align="justify" style="direction:rtl;">واحد پولی پیش فرض می تواند در مسیر تعاریف > واحدهای پول و یا در مسیر تعاریف > زبان ها تنظیم شود. برای تنظیم واحد پول مخصوص به هر زبان ، این مقدار را برابر بلی قرار دهید.</p>';

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('واحد پول مربوط به زبان', 'USE_DEFAULT_LANGUAGE_CURRENCY', '-1', 'به صورت اتوماتیک واحد پول یک زبان انتخاب شود(مثلا : زبان پارسی = ریال)', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function remove() {
      global $osC_Database;

      $osC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('USE_DEFAULT_LANGUAGE_CURRENCY');
    }
  }
?>
