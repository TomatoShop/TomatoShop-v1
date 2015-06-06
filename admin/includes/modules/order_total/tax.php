<?php
/*
  $Id: tax.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_OrderTotal_tax extends osC_OrderTotal_Admin {
    var $_title,
        $_code = 'tax',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_status = false,
        $_sort_order;

    function osC_OrderTotal_tax() {
      global $osC_Language;

      $this->_title = $osC_Language->get('order_total_tax_title');
      $this->_description = $osC_Language->get('order_total_tax_description');
      $this->_status = (defined('MODULE_ORDER_TOTAL_TAX_STATUS') && (MODULE_ORDER_TOTAL_TAX_STATUS == 'true') ? true : false);
      $this->_sort_order = (defined('MODULE_ORDER_TOTAL_TAX_SORT_ORDER') ? MODULE_ORDER_TOTAL_TAX_SORT_ORDER : null);
    }

    function isInstalled() {
      return (bool)defined('MODULE_ORDER_TOTAL_TAX_STATUS');
    }

    function install() {
      global $osC_Database;

      parent::install();

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('نمایش مالیات', 'MODULE_ORDER_TOTAL_TAX_STATUS', 'true', 'آیا مقدار مالیات سفارش نمایش داده شود؟', '6', '1', 'osc_cfg_set_boolean_value(array(\'true\', \'false\'))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ترتیب نمایش', 'MODULE_ORDER_TOTAL_TAX_SORT_ORDER', '50', 'ترتیب نمایش', '6', '2', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_ORDER_TOTAL_TAX_STATUS',
                             'MODULE_ORDER_TOTAL_TAX_SORT_ORDER');
      }

      return $this->_keys;
    }
  }
?>
