<?php
/*
  $Id: session.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_session_Admin {
    var $title,
        $description,
        $uninstallable = false,
        $depends,
        $precedes;

    function osC_Services_session_Admin() {
      global $osC_Language;

      $osC_Language->loadIniFile('modules/services/session.php');

      $this->title = $osC_Language->get('services_session_title');
      $this->description = $osC_Language->get('services_session_description');
    }

    function install() {
      global $osC_Database;

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('اجبار به استفاده از کوکی', 'SERVICE_SESSION_FORCE_COOKIE_USAGE', '-1', 'فقط وقتی یک جلسه ایجاد شود که کوکی فعال باشد', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('بلوک کردن روبات های موتورهای جستجو', 'SERVICE_SESSION_BLOCK_SPIDERS', '-1', 'بلوک کردن روبات موتورهای جستجو از ایجاد یک جلسه کاری', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('SSL Session ID بررسی', 'SERVICE_SESSION_CHECK_SSL_SESSION_ID', '-1', 'HTTPS در هر درخواست صفحه امن SSL_SESSION_ID کنترل', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('بررسی مرورگر کابر', 'SERVICE_SESSION_CHECK_USER_AGENT', '-1', 'در درخواست هر صفحه مرورگر کاربر کنترل شود', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('بررسی آدرس آی پی', 'SERVICE_SESSION_CHECK_IP_ADDRESS', '-1', 'در درخواست هر صفحه آدرس آی پی کنترل شود؟', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('دوباره ساختن Session ID', 'SERVICE_SESSION_REGENERATE_ID', '-1', 'هنگام ورود کابر يا عضويت کاربر session ID دوباره ساختن (requires PHP >= 4.1)', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('حداکثر زمان جلسه کاری', 'SERVICE_SESSION_MAX_LIFETIME', '15', 'جلسه کاری پس از چند دقیقه منقضی شود؟', '6', '0', now())");
    }

    function remove() {
      global $osC_Database;

      $osC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_SESSION_FORCE_COOKIE_USAGE',
                   'SERVICE_SESSION_BLOCK_SPIDERS',
                   'SERVICE_SESSION_CHECK_SSL_SESSION_ID',
                   'SERVICE_SESSION_CHECK_USER_AGENT',
                   'SERVICE_SESSION_CHECK_IP_ADDRESS',
                   'SERVICE_SESSION_REGENERATE_ID', 
                   'SERVICE_SESSION_MAX_LIFETIME');
    }
  }
?>
