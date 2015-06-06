<?php
/*
  $Id: debug.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_debug_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends = 'language',
        $precedes;

    function osC_Services_debug_Admin() {
      global $osC_Language;

      $osC_Language->loadIniFile('modules/services/debug.php');

      $this->title = $osC_Language->get('services_debug_title');
      $this->description = $osC_Language->get('services_debug_description');
    }

    function install() {
      global $osC_Database;

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('مسیر فایل لاگ اجرای صفحات', 'SERVICE_DEBUG_EXECUTION_TIME_LOG', '', 'مسیر فایل ثبت زمان ایجاد صفحه (eg, /www/log/page_parse.log).', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش زمان اجرای صفحه', 'SERVICE_DEBUG_EXECUTION_DISPLAY', '1', 'نمایش زمان اجرای هر صفحه', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('ثبت پرس و جو پایگاه داده', 'SERVICE_DEBUG_LOG_DB_QUERIES', '-1', 'ثبت تمام پرس و جو های دیتابیس در فایل ثبت زمان ایجاد صفحه', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش پرس و جو پایگاه داده', 'SERVICE_DEBUG_OUTPUT_DB_QUERIES', '-1', 'نمایش تمام پرس و جو های ایجاد شده دیتابیس', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش هشدار نسخه توسعه دهندگان', 'SERVICE_DEBUG_SHOW_DEVELOPMENT_WARNING', '1', 'نمایش اخطار استفاده از نسخه توسعه دهندگان', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) values ('بررسی منطقه زبان', 'SERVICE_DEBUG_CHECK_LOCALE', '1', 'نمایش هشدار اگر منطقه زبان تنظیم شده در سرور وجود نداشته باشد', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('install بررسی وجود پوشه', 'SERVICE_DEBUG_CHECK_INSTALLATION_MODULE', '1', 'install نمایش اخطار وجود پوشه', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('بررسی فایل پیکربندی', 'SERVICE_DEBUG_CHECK_CONFIGURATION', '1', 'نمایش اخطار سطح دسترسی نامناسب فایل پیکربندی سیستم', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('بررسی پوشه جلسات', 'SERVICE_DEBUG_CHECK_SESSION_DIRECTORY', '1', 'اگر سیستم جلسات بر حسب ذخیره فایل باشد و پوشه مربوطه وجود نداشته باشد هشدار نمایش داده شود؟', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('بررسی شروع خودکار جلسات', 'SERVICE_DEBUG_CHECK_SESSION_AUTOSTART', '1', 'نمایش هشدار اگر پی اچ پی پیکربندی شده برای شروه خودکار جلسات', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('بررسی پوشه دانلود', 'SERVICE_DEBUG_CHECK_DOWNLOAD_DIRECTORY', '1', 'نمایش اخطار اگر پوشه دانلود کالای دیجیتال وجود نداشته باشد', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش غیر فشرده Css و Javascript', 'SERVICE_DEBUG_SHOW_CSS_JAVASCRIPT', '1', 'نمایش غیر فشرده Css و Javascript در قالب', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function remove() {
      global $osC_Database;

      $osC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_DEBUG_OUTPUT_DB_QUERIES',
                   'SERVICE_DEBUG_LOG_DB_QUERIES',
                   'SERVICE_DEBUG_EXECUTION_TIME_LOG',
                   'SERVICE_DEBUG_EXECUTION_DISPLAY',
                   'SERVICE_DEBUG_SHOW_DEVELOPMENT_WARNING',
                   'SERVICE_DEBUG_CHECK_LOCALE',
                   'SERVICE_DEBUG_CHECK_INSTALLATION_MODULE',
                   'SERVICE_DEBUG_CHECK_CONFIGURATION',
                   'SERVICE_DEBUG_CHECK_SESSION_DIRECTORY',
                   'SERVICE_DEBUG_CHECK_SESSION_AUTOSTART',
                   'SERVICE_DEBUG_CHECK_DOWNLOAD_DIRECTORY', 
      			   'SERVICE_DEBUG_SHOW_CSS_JAVASCRIPT');
    }

  }
?>
