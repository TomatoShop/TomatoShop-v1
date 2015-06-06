<?php
/*
  $Id: categories_tree_panel.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>

Toc.products.CategoriesTreePanel = function(config) {
  config = config || {};
  
  config.region = 'west';
  config.border = false;
  config.autoScroll = true;
  config.containerScroll = true;
  config.split = true;
  config.width = 170;
  config.enableDD = true;
  config.ddGroup = 'productDD';
  config.rootVisible = true;
  
  config.root = new Ext.tree.AsyncTreeNode({
    text: '<?php echo $osC_Language->get("top_category") ?>',
    draggable: false,
    id: '0',
    expanded: true
  });
  config.currentCategoryId = '0';
    
  config.loader = new Ext.tree.TreeLoader({
    dataUrl: Toc.CONF.CONN_URL,
    preloadChildren: true, 
    baseParams: {
      module: 'categories',
      action: 'load_categories_tree'
    },
    listeners: {
      load: function() {
        this.expandAll();
        this.setCategoryId(0);
      },
      scope: this
    }
  });
  
  config.tbar = [{
    text: TocLanguage.btnRefresh,
    iconCls: 'refresh',
    handler: this.refresh,
    scope: this
  }];
  
  config.listeners = {
    "click": this.onCategoryNodeClick, 
    "nodedragover": this.onCategoryNodeDragOver,
    "nodedrop": this.onCategoryNodeDrop,
    "beforenodedrop": this.onBeforeNodeDrop,
    "contextmenu": this.onCategoryNodeRightClick
  };
  
  this.addEvents({'selectchange' : true});
  
  Toc.products.CategoriesTreePanel.superclass.constructor.call(this, config);
};

Ext.extend(Toc.products.CategoriesTreePanel, Ext.tree.TreePanel, {
  
  onBeforeNodeDrop: function(dropEvent) {
    var targetCategoriesId = dropEvent.target.id;
    var products = dropEvent.data.selections;
    
    if ( !Ext.isEmpty(products) ) {
      keys = [];
      for (i = 0; i < products.length; i++) {
        keys.push(products[i].id);
      }
   
      if(keys.length > 0) {
        this.body.mask(TocLanguage.loadingText);
        
        Ext.Ajax.request({
          url: Toc.CONF.CONN_URL,
          params: {
            module: 'products',
            action: 'move_products',
            target_categories_id: targetCategoriesId,
            old_categories_id: this.selectedNode.id,
            batch: keys.join(',')
          },
          callback: function(options, success, response) {
            this.body.unmask();
            result = Ext.decode(response.responseText);
            
            if (result.success == true) {
              var targetNode = this.getNodeById(targetCategoriesId);
              targetNode.select();
              this.fireEvent('selectchange', targetCategoriesId);
            }
          },
          scope: this
        }); 
      }
    }
  },
  
  setCategoryId: function(categoryId) {
    var currentNode = this.getNodeById(categoryId);
    currentNode.select();
    this.currentCategoryId = categoryId;
    this.selectedNode = currentNode;
    
    this.fireEvent('selectchange', categoryId);
  },
  
  onCategoryNodeClick: function (node) {
    node.expand();
    this.setCategoryId(node.id);
  },
  
  onCategoryNodeDragOver: function (e) {
    if (e.target.leaf == true) {
	    e.target.leaf = false;
	  }
	  
	  if (e.target.id == this.selectedNode.id) {
	    return false;
	  }
	  
	  return true;
  },
  
  onCategoryNodeDrop: function(e) {
    if (e.point == 'append') {
      parent_id = e.target.id;
      currentCategoryId = e.target.id;    
    } else {
      parent_id = e.target.parentNode.id;
      currentCategoryId = e.target.parentNode.id;
    }
    
    Ext.Ajax.request ({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'categories',
        action: 'move_categories',
        categories_ids: e.dropNode.id,
        parent_category_id: parent_id
      },
      callback: function(options, success, response){
        result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          this.setCategoryId(currentCategoryId);
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
      },
      scope: this
    });
  },
  
  getCategoriesPath: function(node) {
    var cpath = [];
    node = (node == null) ? this.getNodeById(this.currentCategoryId) : node;
    
    while (node.id > 0) {
      cpath.push(node.id);
      node = node.parentNode;
    }
    
    return cpath.reverse().join('_');
  },
  
  onCategoryNodeRightClick: function(node, event) {
    event.preventDefault();
    node.select();
    
    this.menuContext = new Ext.menu.Menu({
      items: [
        {
          text: TocLanguage.btnAdd,
          iconCls: 'add',
          handler: function() {
            var path = this.getCategoriesPath(node);
            var root = this.root;
            
            TocDesktop.callModuleFunc('categories', 'createCategoriesDialog', function(dlg){
              dlg.on('saveSuccess', function(feedback, categoriesId, text) {
                node.appendChild({
                  id: categoriesId, 
                  text: text, 
                  cls: 'x-tree-node-collapsed', 
                  parent_id: node.id, 
                  leaf: true
                });
                
                node.expand();
              }, this);
              
              dlg.show(null, path);
            });
          },
          scope: this
        },
        {
          text: TocLanguage.tipEdit,
          iconCls: 'edit',
          handler: function() {
            var path = this.getCategoriesPath(node);
            var root = this.root;
            
            TocDesktop.callModuleFunc('categories', 'createCategoriesDialog', function(dlg){
              dlg.on('saveSuccess', function(feedback, categoriesId, text) {
                node.setText(text);
              }, this);
              
              dlg.show(node.id, path);
            });
          },
          scope: this
        },
        {
          text: TocLanguage.tipDelete,
          iconCls: 'remove',
          handler:  function() {
            Ext.MessageBox.confirm(
              TocLanguage.msgWarningTitle, 
              TocLanguage.msgDeleteConfirm, 
              function (btn) {
                if (btn == 'yes') {
                  currentCategoryId = node.parentNode.id;
                  
                  Ext.Ajax.request({
                    url: Toc.CONF.CONN_URL,
                    params: {
                      module: 'categories',
                      action: 'delete_category',
                      categories_id: node.id
                    },
                    callback: function (options, success, response) {
                      var result = Ext.decode(response.responseText);
                      
                      if (result.success == true) {
                        var pNode = node.parentNode;
                        pNode.ui.addClass('x-tree-node-collapsed');
                        
                        node.remove();
                        this.setCategoryId(currentCategoryId);
                      } else {
                        Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
                      }
                    },
                    scope: this
                  });
                }
              }, 
              this
            );
          },
          scope: this
        }
      ]
    });
    
    this.menuContext.showAt(event.getXY());
  },
  
  refresh: function() {
    this.root.reload();
  }
});