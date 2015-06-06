<?php
/*
  $Id: object_info.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_ObjectInfo {
    var $_keys = array();

    function osC_ObjectInfo($array) {
      foreach ($array as $key => $value) {
        $this->_keys[$key] = $value;
      }
    }

    function get($key) {
      return $this->_keys[$key];
    }

    function getAll() {
      return $this->_keys;
    }

    function set($key, $value) {
      $this->_keys[$key] = $value;
    }
  }
?>
