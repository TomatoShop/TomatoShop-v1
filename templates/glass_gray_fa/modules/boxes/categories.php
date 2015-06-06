<?php
/*
  $Id: categories.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>

<!-- box categories start //-->
<div id="boxCategories" class="boxNew">
  <div class="boxTitle"><?php echo $osC_Box->getTitle(); ?></div>

  <div class="boxContents">
      <?php echo $osC_Box->getContent(); ?>
  </div>
</div>

<?php 
  unset($osC_Box);
?>
<!-- box categories end //-->
