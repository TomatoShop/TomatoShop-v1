<?php
/*
  $Id: whats_new.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
?>

<!-- box whats_new start //-->

<div class="boxNew">
  <div class="boxTitle"><?php echo osc_link_object($osC_Box->getTitleLink(), $osC_Box->getTitle()); ?></div>

  <div class="boxContents" style="text-align: center;"><?php echo $osC_Box->getContent(); ?></div>
</div>

<!-- box whats_new end //-->
