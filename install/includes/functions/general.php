<?php
/*
  $Id: general.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  function osc_realpath($directory) {
    return str_replace('\\', '/', realpath($directory));
  }

  function toc_copy($source, $target) {
    if (is_dir($source)) {
      $src_dir = dir($source);

      while ( false !== ($file = $src_dir->read()) ) {
        if ($file == '.' || $file == '..') {
          continue;
        }

        $src_file = $source . '/' . $file;
        if (is_dir($src_file)) {
          toc_copy($src_file, $target . '/' . $file );
          continue;
        }
        copy( $src_file, $target . '/' . $file );
      }

      $src_dir->close();
    }else {
      copy($source, $target);
    }
  }
?>
