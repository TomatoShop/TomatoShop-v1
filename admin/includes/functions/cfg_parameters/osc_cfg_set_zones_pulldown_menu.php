<?php
/*
  $Id: osc_cfg_set_zones_pulldown_menu.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  function osc_cfg_set_zones_pulldown_menu($default, $key = null) {
    $name = (!empty($key) ? 'configuration[' . $key . ']' : 'configuration_value');

    $zones_array = array();

    foreach (osC_Address::getZones() as $zone) {
      $zones_array[] = array('id' => $zone['id'],
                             'text' => $zone['name'],
                             'group' => $zone['country_name']);
    }

    $control = array();
    $control['name'] = $name;
    $control['type'] = 'combobox';
    $control['mode'] = 'remote';
    $control['module'] = 'cfg';
    $control['action'] = 'get_zones';

    return $control;  
  }
?>