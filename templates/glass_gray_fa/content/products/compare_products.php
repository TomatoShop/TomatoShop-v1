<?php
/*
  $Id: compare_products.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>

<style type="text/css">
<!--
#pageWrapper {
  margin-right: 20px;
  padding: 0;
  float: right;
}

#pageContent {
  width: 100%;
  margin: 0;
  padding: 0;
}

div#pageBlockLeft {
  width: 0;
  margin: 0;
}
//-->
</style>

  <h1><?php echo $osC_Language->get('compare_products_heading'); ?></h1>

  <div>
		<?php
		  echo $toC_Compare_Products->outputCompareProductsTable();
		?>

    <p align="left"><?php echo osc_link_object('javascript:window.close();', $osC_Language->get('close_window')); ?></p>
  </div>
