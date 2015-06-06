<?php
/*
  $Id: bank_receipt.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class toC_BankReceipt {
  
    function &getOrderList() {
      global $osC_Database, $osC_Customer;

      $Qbankreceipt = $osC_Database->query('select orders_id, date_purchased from :table_orders where customers_id = :customers_id order by orders_id');
      $Qbankreceipt->bindTable(':table_orders', TABLE_ORDERS);
      $Qbankreceipt->bindInt(':customers_id', $osC_Customer->getID());
      $Qbankreceipt->execute();    
    
      return $Qbankreceipt; 
    }
  }
?>
