<?php
/*
  $Id: orders_products_grid.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>
Toc.orders.OrdersProductsGrid = function(config) {

  config = config || {};
  
  config.title = '<?php echo $osC_Language->get('section_products'); ?>';
  config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
  
  config.ds = new Ext.data.Store({
    url: Toc.CONF.CONN_URL,
    baseParams: {
      module: 'orders',
      action: 'list_order_products',
      orders_id: config.ordersId    
    },
    reader: new Ext.data.JsonReader({
      root: Toc.CONF.JSON_READER_ROOT,
      id: 'orders_id'
    },[
      'orders_id',
      'products',
      'sku',
      'tax',
      'price_net',
      'price_gross',
      'total_net',
      'total_gross',
      'return_quantity'
    ]),
    autoLoad: true
  });
  
  config.cm = new Ext.grid.ColumnModel([
    {id: 'orders-products', header: '<?php echo $osC_Language->get('table_heading_products');?>', dataIndex: 'products'},
    {header: '<?php echo $osC_Language->get('table_heading_product_sku');?>', dataIndex: 'sku', width: 80, align: 'right'},
    {header: '<?php echo $osC_Language->get('table_heading_tax');?>', dataIndex: 'tax', width: 80, align: 'right'},
    {header: '<?php echo $osC_Language->get('table_heading_price_gross');?>', dataIndex: 'price_gross', width: 120, align: 'right'},
    {header: '<?php echo $osC_Language->get('table_heading_total_gross');?>', dataIndex: 'total_gross', width: 120, align: 'right'},
    {header: '<?php echo $osC_Language->get('table_heading_return_quantity');?>', dataIndex: 'return_quantity', width: 100, align: 'center'}
  ]);
  config.autoExpandColumn = 'orders-products';
  
  Toc.orders.OrdersProductsGrid.superclass.constructor.call(this, config);
};

Ext.extend(Toc.orders.OrdersProductsGrid, Ext.grid.GridPanel);