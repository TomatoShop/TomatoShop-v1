<?php
/*
  $Id: recently_visited.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Services_recently_visited_Admin {
    var $title,
        $description,
        $uninstallable = true,
        $depends = array('session', 'category_path'),
        $precedes;

    function osC_Services_recently_visited_Admin() {
      global $osC_Language;

      $osC_Language->loadIniFile('modules/services/recently_visited.php');

      $this->title = $osC_Language->get('services_recently_visited_title');
      $this->description = $osC_Language->get('services_recently_visited_description');
    }

    function install() {
      global $osC_Database;

      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش آخرین محصولات', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCTS', '1', 'نمایش آخرین محصولات مشاهده شده', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش تصویر محصولات', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_IMAGES', '1', 'نمایش تصویر محصولات', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش قیمت محصولات', 'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_PRICES', '1', 'نمایش قیمت محصولات', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('حداکثر محصولات برای نمایش', 'SERVICE_RECENTLY_VISITED_MAX_PRODUCTS', '5', 'حداکثر تعداد آخرین محصولات مشاهده شده جهت نمایش', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش آخرین شاخه های محصولات', 'SERVICE_RECENTLY_VISITED_SHOW_CATEGORIES', '1', 'نمایش آخرین شاخه های محصولات مشاهده شده', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('حداکثر تعداد شاخه ها برای نمایش', 'SERVICE_RECENTLY_VISITED_MAX_CATEGORIES', '3', 'حداکثر تعداد آخرین شاخه های مشاهده شده جهت نمایش', '6', '0', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, use_function, set_function, date_added) VALUES ('نمایش آخرین جستجوها', 'SERVICE_RECENTLY_VISITED_SHOW_SEARCHES', '1', 'مشاهده آخرین جستجوها', '6', '0', 'osc_cfg_use_get_boolean_value', 'osc_cfg_set_boolean_value(array(1, -1))', now())");
      $osC_Database->simpleQuery("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('حداکثر جستجوها برای نمایش', 'SERVICE_RECENTLY_VISITED_MAX_SEARCHES', '3', 'حداکثر تعداد آخرین جستجوها جهت نمایش', '6', '0', now())");
    }

    function remove() {
      global $osC_Database;

      $osC_Database->simpleQuery("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('SERVICE_RECENTLY_VISITED_SHOW_PRODUCTS',
                   'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_IMAGES',
                   'SERVICE_RECENTLY_VISITED_SHOW_PRODUCT_PRICES',
                   'SERVICE_RECENTLY_VISITED_MAX_PRODUCTS',
                   'SERVICE_RECENTLY_VISITED_SHOW_CATEGORIES',
                   'SERVICE_RECENTLY_VISITED_MAX_CATEGORIES',
                   'SERVICE_RECENTLY_VISITED_SHOW_SEARCHES',
                   'SERVICE_RECENTLY_VISITED_MAX_SEARCHES');
    }
  }
?>
