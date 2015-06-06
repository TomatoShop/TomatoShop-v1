<?php
/*
  $Id: products_attributes_groups_grid.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>
Toc.products_attributes.AttributeGroupsGrid = function(config) {

  config = config || {};
  
  config.region = 'center';
  config.border = false;
  config.viewConfig = {emptyText: TocLanguage.gridNoRecords}; 
  
  config.ds = new Ext.data.Store({
    url: Toc.CONF.CONN_URL,
    baseParams: {
      module: 'products_attributes',
      action: 'list_products_attributes'        
    },
    reader: new Ext.data.JsonReader({
      root: Toc.CONF.JSON_READER_ROOT,
      totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY,
      id: 'products_attributes_groups_id'
    },[
      'products_attributes_groups_id',
      'products_attributes_groups_name',
      'total_entries'
    ]),
    autoLoad: true
  });
  
  config.rowActions = new Ext.ux.grid.RowActions({
    actions: [
     {iconCls: 'icon-edit-record', qtip: TocLanguage.tipEdit},
     {iconCls: 'icon-delete-record', qtip: TocLanguage.tipDelete}],
    widthIntercept: Ext.isSafari ? 4: 2
  });
  config.rowActions.on('action', this.onRowAction, this);    
  config.plugins = config.rowActions;  
     
  config.cm = new Ext.grid.ColumnModel([
    {id: 'products_attributes_groups_name', header: '<?php echo $osC_Language->get('table_heading_attributes_groups');?>', dataIndex: 'products_attributes_groups_name'},
    {header: '<?php echo $osC_Language->get('table_heading_total_entries');?>', dataIndex: 'total_entries', width: 100, align: 'right'},
    config.rowActions
  ]);
  config.selModel = new Ext.grid.RowSelectionModel({singleSelect: true});
  config.autoExpandColumn = 'products_attributes_groups_name';
   
  config.listeners = {
    'rowclick' : this.onGrdRowClick
  };

  config.tbar = [
    {
      text: TocLanguage.btnAdd,
      iconCls: 'add',
      handler: this.onAdd,
      scope: this
    },
    '-',
    { 
      text: TocLanguage.btnRefresh,
      iconCls:'refresh',
      handler: this.onRefresh,
      scope: this
    }];
    
  config.bbar = new Ext.PagingToolbar({
    pageSize: Toc.CONF.GRID_PAGE_SIZE,
    store: config.ds,
    iconCls: 'icon-grid',
    displayInfo: true,
    displayMsg: TocLanguage.displayMsg,
    emptyMsg: TocLanguage.emptyMsg
  });
  
  this.addEvents({'selectchange' : true});  
  
  Toc.products_attributes.AttributeGroupsGrid.superclass.constructor.call(this, config);
};

Ext.extend(Toc.products_attributes.AttributeGroupsGrid, Ext.grid.GridPanel, {
  
  onAdd: function() {
    var dlg = this.owner.createAttributeGroupsDialog();
    
    dlg.on('saveSuccess', function() {
      this.onRefresh();
    }, this);
    
    dlg.show();
  },
  
  onEdit: function(record) {
    var dlg = this.owner.createAttributeGroupsDialog();
    dlg.setTitle(record.get('products_attributes_groups_name'));
    
    dlg.on('saveSuccess', function() {
      this.onRefresh();
    }, this);

    dlg.show(record.get('products_attributes_groups_id'));
  },
  
  onDelete: function(record) {
    var groupsId = record.get('products_attributes_groups_id');
    
    Ext.Msg.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function(btn) {
        if(btn == 'yes') { 
          Ext.Ajax.request({
            url: Toc.CONF.CONN_URL,
            params: { 
              module: 'products_attributes',
              action: 'delete_products_attributes',
              products_attributes_groups_id: groupsId                                        
            },
            callback: function(options, success, response) {
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.owner.app.showNotification({title: TocLanguage.msgSuccessTitle, html: result.feedback});
                this.getStore().reload();
              } else {
                Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
              }
            }, 
            scope: this 
          });
        }
      }, this);                                                               
  },
  
  onGrdRowClick: function(grid, rowIndex, e) {
    var record = grid.getStore().getAt(rowIndex);
    this.fireEvent('selectchange', record);
  },
      
  onRefresh: function() {
    this.getStore().reload();
  },  
  
  onRowAction: function(grid, record, action, row, col) {
    switch(action) {
      case 'icon-delete-record':
        this.onDelete(record);
        break;
      
      case 'icon-edit-record':
        this.onEdit(record);
        break;
    }
  }
});