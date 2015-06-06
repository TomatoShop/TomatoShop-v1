<?php
/*
  $Id: cheque.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

/**
 * The administration side of the Cheque payment module
 */

  class osC_Payment_cheque extends osC_Payment_Admin {

/**
 * The administrative title of the payment module
 *
 * @var string
 * @access private
 */

    var $_title;

/**
 * The code of the payment module
 *
 * @var string
 * @access private
 */

    var $_code = 'cheque';

/**
 * The developers name
 *
 * @var string
 * @access private
 */

    var $_author_name = 'TomatoCart';

/**
 * The developers address
 *
 * @var string
 * @access private
 */

    var $_author_www = 'http://www.tomatocart.com';

/**
 * The status of the module
 *
 * @var boolean
 * @access private
 */

    var $_status = false;

/**
 * Constructor
 */

    function osC_Payment_cheque() {
      global $osC_Language;

      $this->_title = $osC_Language->get('payment_cheque_title');
      $this->_description = $osC_Language->get('payment_cheque_description');
      $this->_method_title = $osC_Language->get('payment_cheque_method_title');
      $this->_status = (defined('MODULE_PAYMENT_CHEQUE_STATUS') && (MODULE_PAYMENT_CHEQUE_STATUS == '1') ? true : false);
      $this->_sort_order = (defined('MODULE_PAYMENT_CHEQUE_SORT_ORDER') ? MODULE_PAYMENT_CHEQUE_SORT_ORDER : null);
    }

/**
 * Checks to see if the module has been installed
 *
 * @access public
 * @return boolean
 */

    function isInstalled() {
      return (bool)defined('MODULE_PAYMENT_CHEQUE_STATUS');
    }

/**
 * Installs the module
 *
 * @access public
 * @see osC_Payment_Admin::install()
 */

    function install() {
      global $osC_Database;

      parent::install();

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('فعال سازی ماژول چک', 'MODULE_PAYMENT_CHEQUE_STATUS', '-1', 'پرداخت از طریق چکمورد قبول شما می باشد؟', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('منطقه پرداخت', 'MODULE_PAYMENT_CHEQUE_ZONE', '0', 'اگر منطقه ای انتخاب شود این روش فقط برای آن منطقه قابل استفاده است', '6', '0', 'osc_cfg_use_get_zone_class_title', 'osc_cfg_set_zone_classes_pull_down_menu', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('ترتیب نمایش', 'MODULE_PAYMENT_CHEQUE_SORT_ORDER', '0', 'ترتیب نمایش ، مقادیر کمتر بالاتر قرار می گیرند', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, use_function, date_added) values ('تنظیم وضعیت سفارش', 'MODULE_PAYMENT_CHEQUE_ORDER_STATUS_ID', '" . DEFAULT_ORDERS_STATUS_ID . "', 'اگر از طریق این ماژول پرداخت انجام شد ، وضعیت سفارش بر روی وضعیت تنظیم شده قرار می گیرد', '6', '0', 'osc_cfg_set_order_statuses_pull_down_menu', 'osc_cfg_use_get_order_status_title', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('در وجه', 'MODULE_PAYMENT_CHEQUE_ACCOUNT_OWNER', '', 'اسمی که چک باید درمشتری باید چک را در وجه آن صادر نماید', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('آدرس', 'MODULE_PAYMENT_CHEQUE_ADDRESS', '', 'آدرسی که مشتری باید چک را به آن ارسال نماید.', '6', '0', 'osc_cfg_set_textarea_field', now())");

  }

/**
 * Return the configuration parameter keys in an array
 *
 * @access public
 * @return array
 */

    function getKeys() {
      if (!isset($this->_keys)) {
        $this->_keys = array('MODULE_PAYMENT_CHEQUE_STATUS',
                             'MODULE_PAYMENT_CHEQUE_ZONE',
                             'MODULE_PAYMENT_CHEQUE_SORT_ORDER',
                             'MODULE_PAYMENT_CHEQUE_ORDER_STATUS_ID',
                             'MODULE_PAYMENT_CHEQUE_ACCOUNT_OWNER',
                             'MODULE_PAYMENT_CHEQUE_ADDRESS');
      }

      return $this->_keys;
    }
  }
?>

