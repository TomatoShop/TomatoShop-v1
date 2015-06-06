<?php
/*
  $Id: low_order_fee.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_OrderTotal_low_order_fee extends osC_OrderTotal_Admin {
    var $_title,
        $_code = 'low_order_fee',
        $_author_name = 'osCommerce',
        $_author_www = 'http://www.oscommerce.com',
        $_status = false,
        $_sort_order;

    function osC_OrderTotal_low_order_fee() {
      global $osC_Language;

      $this->_title = $osC_Language->get('order_total_loworderfee_title');
      $this->_description = $osC_Language->get('order_total_loworderfee_description');
      $this->_status = (defined('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS') && (MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS == 'true') ? true : false);
      $this->_sort_order = (defined('MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER') ? MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER : null);
    }

    function isInstalled() {
      return (bool)defined('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS');
    }

    function install() {
      global $osC_Database;

      parent::install();

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('نمایش حداقل هزینه سفارش', 'MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS', 'true', 'آیا حداقل هزینه سفارش نمایش داده شود؟', '6', '1', 'osc_cfg_set_boolean_value(array(\'true\', \'false\'))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ترتیب نمایش', 'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER', '30', 'ترتیب نمایش', '6', '2', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('سفارشات کمتر از مقدار حداقل', 'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE', 'false', 'آیا امکان ایجاد سفارش با مبلغ کمتر از حداقل هزینه سفارش وجود داشته باشد؟', '6', '3', 'osc_cfg_set_boolean_value(array(\'true\', \'false\'))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('هزینه سفارش برای مبالغ کمتر', 'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER', '50', 'این مبلغ به سفارشاتی که مبلغ آنها از کمترین مبلغ سفارش کمتر باشد افزوده می گردد', '6', '4', 'currencies->format', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, date_added) values ('کمترین مبلغ سفارش', 'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE', '5', 'کمترین مبلغ سفارش', '6', '5', 'currencies->format', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('افزودن هزینه سفارش', 'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION', 'both', 'افزودن هزینه سفارش به سفارشات کمتر از میزان حداقل در سفارشات  ارسالی به مقصد های داخلی یا خارجی یا هر دو؟', '6', '6', 'osc_cfg_set_boolean_value(array(\'national\', \'international\', \'both\'))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('کلاس مالیاتی', 'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS', '0', 'استفاده از کلاس مالیاتی زیر برای حداقل مبلغ سفارش', '6', '7', 'osc_cfg_use_get_tax_class_title', 'osc_cfg_set_tax_classes_pull_down_menu', now())");
    }

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_ORDER_TOTAL_LOWORDERFEE_STATUS',
                             'MODULE_ORDER_TOTAL_LOWORDERFEE_SORT_ORDER',
                             'MODULE_ORDER_TOTAL_LOWORDERFEE_LOW_ORDER_FEE',
                             'MODULE_ORDER_TOTAL_LOWORDERFEE_ORDER_UNDER',
                             'MODULE_ORDER_TOTAL_LOWORDERFEE_FEE',
                             'MODULE_ORDER_TOTAL_LOWORDERFEE_DESTINATION',
                             'MODULE_ORDER_TOTAL_LOWORDERFEE_TAX_CLASS');
      }

      return $this->_keys;
    }
  }
?>
