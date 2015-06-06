<?php
/*
  $Id: xsell_products.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Content_xsell_products extends osC_Modules {
    var $_title,
        $_code = 'xsell_products',
        $_author_name = 'TomatoCart',
        $_author_www = 'http://www.tomatocart.com',
        $_group = 'content';

/* Class constructor */

    function osC_Content_xsell_products() {
      global $osC_Language;

      $this->_title = $osC_Language->get('xsell_products_title');
    }

    function initialize() {
      global $osC_Database, $osC_Language, $osC_Product, $osC_Image;

      if (isset($osC_Product)) {
        $Qproducts = $osC_Database->query('select p.products_id, pd.products_name, i.image from :table_products_xsell px left join :table_products_images i on (px.xsell_products_id = i.products_id and i.default_flag = :default_flag), :table_products p, :table_products_description pd where px.xsell_products_id = p.products_id and p.products_id = pd.products_id and px.products_id = :products_id and p.products_status = 1 and pd.language_id = :language_id');
        $Qproducts->bindTable(':table_products_xsell', TABLE_PRODUCTS_XSELL);
        $Qproducts->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
        $Qproducts->bindTable(':table_products_description', TABLE_PRODUCTS_DESCRIPTION);
        $Qproducts->bindTable(':table_products', TABLE_PRODUCTS);
        $Qproducts->bindInt(':default_flag', 1);
        $Qproducts->bindInt(':products_id', $osC_Product->getID());
        $Qproducts->bindInt(':language_id', $osC_Language->getID());
        $Qproducts->execute();

        if ($Qproducts->numberOfRows() > 0) {
		  $i = 0;
          while ($Qproducts->next()) {
          if(($i % 3 == 0) && ($i != 0))
            $this->_content .= '<div style="clear:both"></div>';
	
          $this->_content .= '<div style="margin-top: 10px; float:right; width: 33%; text-align: center">' .
                             osc_link_object(osc_href_link(FILENAME_PRODUCTS, $Qproducts->value('products_id')), $osC_Image->show($Qproducts->value('image'), $Qproducts->value('products_name'))) .
                             '<span style="display:block; height: 32px; text-align: center">' . osc_link_object(osc_href_link(FILENAME_PRODUCTS, $Qproducts->value('products_id')), $Qproducts->value('products_name')) . '</span>' . 
                             '</div>';
							 
          $i++;
          }

        $this->_content .= '<div style="clear:both"></div>';
        }

        $Qproducts->freeResult();
      }
    }
  }
?>
