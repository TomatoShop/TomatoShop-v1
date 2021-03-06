<?php
/*
  $Id: selfpickup.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Shipping_selfpickup extends osC_Shipping {
    var $icon;

    var $_title,
        $_code = 'selfpickup',
        $_status = false,
        $_sort_order;

// class constructor
    function osC_Shipping_selfpickup() {
      global $osC_Language;

      $this->icon = '';

      $this->_title = $osC_Language->get('shipping_self_pickup_title');
      $this->_description = $osC_Language->get('shipping_self_pickup_description');
      $this->_status = (defined('MODULE_SHIPPING_SELF_PICKUP_STATUS') && (MODULE_SHIPPING_SELF_PICKUP_STATUS == 'True') ? true : false);
      $this->_sort_order = (defined('MODULE_SHIPPING_SELF_PICKUP_SORT_ORDER') ? MODULE_SHIPPING_SELF_PICKUP_SORT_ORDER : null);
    }

// class methods
    function initialize() {
      global $osC_Database, $osC_ShoppingCart;

      if ( ($this->_status === true) && ((int)MODULE_SHIPPING_SELF_PICKUP_ZONE > 0) ) {
        $check_flag = false;

        $Qcheck = $osC_Database->query('select zone_id from :table_zones_to_geo_zones where geo_zone_id = :geo_zone_id and zone_country_id = :zone_country_id order by zone_id');
        $Qcheck->bindTable(':table_zones_to_geo_zones', TABLE_ZONES_TO_GEO_ZONES);
        $Qcheck->bindInt(':geo_zone_id', MODULE_SHIPPING_SELF_PICKUP_ZONE);
        $Qcheck->bindInt(':zone_country_id', $osC_ShoppingCart->getShippingAddress('country_id'));
        $Qcheck->execute();

        while ($Qcheck->next()) {
          if ($Qcheck->valueInt('zone_id') < 1) {
            $check_flag = true;
            break;
          } elseif ($Qcheck->valueInt('zone_id') == $osC_ShoppingCart->getShippingAddress('zone_id')) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->_status = false;
        }
      }
    }

    function quote() {
      global $osC_Language;
      
      $this->quotes = array('id' => $this->_code,
                            'module' => $this->_title,
                            'methods' => array(array('id' => $this->_code,
                                                     'title' => $osC_Language->get('shipping_self_pickup_method'),
                                                     'cost' => 0)),
                            'tax_class_id' => 0);

      if (!empty($this->icon)) $this->quotes['icon'] = osc_image($this->icon, $this->_title);

      return $this->quotes;
    }
  }
?>
