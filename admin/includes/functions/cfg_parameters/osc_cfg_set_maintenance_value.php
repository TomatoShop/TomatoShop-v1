<?php
/*
  $Id: osc_cfg_set_maintenance_value.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  function osc_cfg_set_maintenance_value($id) {
    global $osC_Language;

    switch ($id) {
     case 0: return $osC_Language->get('operation_heading_deactivate'); break;
     case 1: return $osC_Language->get('operation_heading_activate'); break;    
    }
  }
?>