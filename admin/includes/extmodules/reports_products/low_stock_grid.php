<?php
/*
  $Id: low_stock_grid.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>

  Toc.reports_products.LowStockGrid = function(config) {
    
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.ds = new Ext.data.Store({
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'reports_products',
        action: 'list_low_stock'
      },
      autoLoad: true,
      reader: new Ext.data.JsonReader({
        root: Toc.CONF.JSON_READER_ROOT,
        totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY,
        id: 'products_id'
      }, [
        'products_id',
        'products_name',
        'products_sku',
        'products_status',
        'products_quantity'
      ])
    }); 
    
    renderStatus = function(status) {
	    if(status == 1) {
	      return '<img class="img-button" src="images/icon_status_green.gif" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="images/icon_status_red_light.gif" />';
	    }else {
	      return '<img class="img-button btn-status-on" style="cursor: pointer" src="images/icon_status_green_light.gif" />&nbsp;<img class="img-button" src= "images/icon_status_red.gif" />';
	    }
	  }; 
    
    config.cm = new Ext.grid.ColumnModel([
      { id: 'products_name', header: '<?php echo $osC_Language->get('table_heading_products'); ?>',dataIndex: 'products_name'},
      { header: '<?php echo $osC_Language->get('table_heading_sku'); ?>', align: 'center', sortable: true, dataIndex: 'products_sku', width:180},
      { header: "<?php echo $osC_Language->get('table_heading_status'); ?>", align: 'center', renderer: renderStatus, sortable: true, dataIndex: 'products_status', width: 80},
      { header: '<?php echo $osC_Language->get('table_heading_quantity'); ?>',dataIndex: 'products_quantity', sortable: true, width: 150, align: 'right'}
    ]);
    config.autoExpandColumn = 'products_name';
    
    dsCategories = new Ext.data.Store({
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'reports_products',
        action: 'get_categories'
      },
      autoLoad: true,
      reader: new Ext.data.JsonReader({
        root: Toc.CONF.JSON_READER_ROOT,
        totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
      }, [
        'id',
        'text'
      ])
    });
    
    config.cboCategories = new Toc.CategoriesComboBox({
      store: dsCategories,
      valueField: 'id',
      displayField: 'text',
      readOnly: true,
      mode: 'local',
      emptyText: '<?php echo $osC_Language->get("top_category"); ?>',
      triggerAction: 'all',
      listeners: {
        select: this.onCboCategoriesSelect,
        scope: this
      }
    });
    
    config.tbar = [
      { 
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      },
      '->',
      config.cboCategories
    ];
    
    config.bbar = new Ext.PageToolbar({
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      store: config.ds,
      steps: Toc.CONF.GRID_STEPS,
      beforePageText: TocLanguage.beforePageText,
      firstText: TocLanguage.firstText,
      lastText: TocLanguage.lastText,
      nextText: TocLanguage.nextText,
      prevText: TocLanguage.prevText,
      afterPageText: TocLanguage.afterPageText,
      refreshText: TocLanguage.refreshText,
      displayInfo: true,
      displayMsg: TocLanguage.displayMsg,
      emptyMsg: TocLanguage.emptyMsg,
      prevStepText: TocLanguage.prevStepText,
      nextStepText: TocLanguage.nextStepText
    });
    
    Toc.reports_products.LowStockGrid.superclass.constructor.call(this, config);
  };
  
  Ext.extend(Toc.reports_products.LowStockGrid, Ext.grid.GridPanel, {
    onCboCategoriesSelect: function() {
      var categoriesId = this.cboCategories.getValue() || null;
      var store = this.getStore();
      
      store.baseParams['categories_id'] = categoriesId;
      store.reload();
    },
    
    onClick: function(e, target) {
	    var t = e.getTarget(),
	        v = this.view,
	        row = v.findRowIndex(t),
	        col = v.findCellIndex(t),
	        action = false,
	        module;
	        
	    if (row !== false) {
	      var btn = e.getTarget(".img-button");
	      
	      if (btn) {
	        action = btn.className.replace(/img-button btn-/, '').trim();
	      }
	
	      if (action != 'img-button') {
	        var productsId = this.getStore().getAt(row).get('products_id');
	        var colname = this.getColumnModel().getDataIndex(col);
	        
	        if(colname == 'products_status') {
	          module = 'set_status';
	        }
	
	        switch(action) {
	          case 'status-off':
	          case 'status-on':
	            flag = (action == 'status-on') ? 1 : 0;
	            this.onAction(module, productsId, flag);
	            break;
	        }
	      }
	    }
	  },
  
	  onAction: function(action, productsId, flag) {
	    Ext.Ajax.request({
	      url: Toc.CONF.CONN_URL,
	      params: {
	        module: 'reports_products',
	        action: action,
	        products_id: productsId,
	        flag: flag
	      },
	      callback: function(options, success, response) {
	        var result = Ext.decode(response.responseText);
	        
	        if (result.success == true) {
            this.getStore().getById(productsId).set('products_status', flag);
	          this.getStore().commitChanges();
	          
	          this.owner.app.showNotification({title: TocLanguage.msgSuccessTitle, html: result.feedback});
	        } else {
	          this.owner.app.showNotification({title: TocLanguage.msgSuccessTitle, html: result.feedback});
	        }
	      },
	      scope: this
	    });
	  },
    
    onRefresh: function() { 
      this.getStore().reload();
    }
  });