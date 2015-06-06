<?php
/*
  $Id: wishlist_add.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class osC_Actions_wishlist_add {
    function execute() {
      global $osC_Session, $toC_Wishlist, $osC_Product, $messageStack, $osC_Language, $osC_Customer, $osC_NavigationHistory;
      
      if ($osC_Customer->isLoggedOn() === false) {
      	$osC_NavigationHistory->setSnapshot();
      
      	osc_redirect(osc_href_link(FILENAME_ACCOUNT, 'login', 'SSL'));
      }
      
      //load the language definitions in the account group
      $osC_Language->load('account');
      
      $id = false;

      foreach ($_GET as $key => $value) {
        if ( (preg_match('/^[0-9]+(_?([0-9]+:?[0-9]+)+(;?([0-9]+:?[0-9]+)+)*)*$/', $key) || preg_match('/^[a-zA-Z0-9 -_]*$/', $key)) && ($key != $osC_Session->getName()) ) {
          $id = $key;
        }

        break;
      }
      
      //change the variants in the product info page, then attach the wid param to represent the variant product
      if (isset($_GET['wid']) && preg_match('/^[0-9]+(_?([0-9]+:?[0-9]+)+(;?([0-9]+:?[0-9]+)+)*)*$/', $_GET['wid'])) {
        $id = $_GET['wid'];
      }
      
      if (strpos( $id, '_') !== false) {
        $id = str_replace('_', '#', $id);
      }

      if (($id !== false) && osC_Product::checkEntry($id)) {
        $osC_Product = new osC_Product($id);
      }
      
      if (isset($osC_Product)) {       
				$result = $toC_Wishlist->add($id);      
        
        if ($result === true) {
        	$messageStack->add_session('wishlist', $osC_Language->get('success_wishlist_entry_updated'), 'success');
        }else {
        	$messageStack->add_session('wishlist', $osC_Language->get('error_wishlist_product_existed'));
        }
      }

      osc_redirect(osc_href_link(FILENAME_ACCOUNT, 'wishlist'));
    }
  }
?>
