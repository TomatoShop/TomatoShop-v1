<?php
/*
  $Id: reviews.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_reviews_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends,
        $precedes;

    function osC_Services_reviews_Admin() {
      global $osC_Language;

      $osC_Language->loadIniFile('modules/services/reviews.php');

      $this->title = $osC_Language->get('services_reviews_title');
      $this->description = $osC_Language->get('services_reviews_description');
    }

    function install() {
      global $osC_Database;

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('نظرات جدید', 'MAX_DISPLAY_NEW_REVIEWS', '6', 'حداکثر تعداد نظرات جدید برای نمایش', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('نظر توسط کاربران؟', 'SERVICE_REVIEW_ENABLE_REVIEWS', '1', 'فقط مشتریان بتوانند نظر بدهند؟', '6', '0', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('مدیریت نظرات', 'SERVICE_REVIEW_ENABLE_MODERATION', '-1', 'نظرات پس از تایید مدیر سایت نمایش داده شود؟', '6', '0', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
    }

    function remove() {
      global $osC_Database;

      $osC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MAX_DISPLAY_NEW_REVIEWS',
                   'SERVICE_REVIEW_ENABLE_REVIEWS',
                   'SERVICE_REVIEW_ENABLE_MODERATION');
    }
  }
?>
