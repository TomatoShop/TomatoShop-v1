<?php
/*
  $Id: flat.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Shipping_flat extends osC_Shipping_Admin {
    var $icon;

    var $_title,
        $_code = 'flat',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_status = false,
        $_sort_order;

// class constructor
    function osC_Shipping_flat() {
      global $osC_Language;

      $this->icon = '';

      $this->_title = $osC_Language->get('shipping_flat_title');
      $this->_description = $osC_Language->get('shipping_flat_description');
      $this->_status = (defined('MODULE_SHIPPING_FLAT_STATUS') && (MODULE_SHIPPING_FLAT_STATUS == 'True') ? true : false);
      $this->_sort_order = (defined('MODULE_SHIPPING_FLAT_SORT_ORDER') ? MODULE_SHIPPING_FLAT_SORT_ORDER : null);
    }

// class methods
    function isInstalled() {
      return (bool)defined('MODULE_SHIPPING_FLAT_STATUS');
    }

    function install() {
      global $osC_Database;

      parent::install();

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('فعال کردن هزینه یکنواخت', 'MODULE_SHIPPING_FLAT_STATUS', 'True', 'هزینه حمل و نقل یکنواخت فعال گردد؟', '6', '0', 'osc_cfg_set_boolean_value(array(\'True\', \'False\'))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('هزینه حمل و نقل', 'MODULE_SHIPPING_FLAT_COST', '5.00', 'هزینه حمل و نقل برای تمامی سفارشاتی که از این روش استفاده می کنند', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('کلاس مالیاتی', 'MODULE_SHIPPING_FLAT_TAX_CLASS', '0', 'از کلاس مالیاتی زیر برای هزینه حمل و نقل استفاده شود', '6', '0', 'osc_cfg_use_get_tax_class_title', 'osc_cfg_set_tax_classes_pull_down_menu', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('منطقه حمل و نقل', 'MODULE_SHIPPING_FLAT_ZONE', '0', 'اگر منطقه ای انتخاب شود این روش فقط برای آن منطقه قابل استفاده است', '6', '0', 'osc_cfg_use_get_zone_class_title', 'osc_cfg_set_zone_classes_pull_down_menu', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ترتیب نمایش', 'MODULE_SHIPPING_FLAT_SORT_ORDER', '0', 'ترتیب نمایش', '6', '0', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_SHIPPING_FLAT_STATUS',
                             'MODULE_SHIPPING_FLAT_COST',
                             'MODULE_SHIPPING_FLAT_TAX_CLASS',
                             'MODULE_SHIPPING_FLAT_ZONE',
                             'MODULE_SHIPPING_FLAT_SORT_ORDER');
      }

      return $this->_keys;
    }
  }
?>
