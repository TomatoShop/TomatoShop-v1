<?php
/*
  $Id: sms_templates_grid.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/

?>

Toc.sms_templates.SmsTemplatesGrid = function(config) {
  
  config = config || {};
  
  config.border = false;
  config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
  
  config.ds = new Ext.data.Store({
    url: Toc.CONF.CONN_URL,
    baseParams: {
      module: 'sms_templates',
      action: 'list_sms_templates'        
    },
    reader: new Ext.data.JsonReader({
      root: Toc.CONF.JSON_READER_ROOT,
      totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY,
      id: 'sms_templates_id'
    }, 
    [
      'sms_templates_id',
      'sms_templates_name',
      'sms_title',
      'sms_templates_status'
    ]),
    autoLoad: true
  });
  
  config.rowActions = new Ext.ux.grid.RowActions({
    actions:[
      {iconCls: 'icon-edit-record', qtip: TocLanguage.tipEdit}
    ],
    widthIntercept: Ext.isSafari ? 4 : 2
  });
  config.rowActions.on('action', this.onRowAction, this);    
  config.plugins = config.rowActions;
  
  renderPublish = function(status) {
    if(status == 1) {
      return '<img class="img-button" src="images/icon_status_green.gif" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="images/icon_status_red_light.gif" />';
    }else {
      return '<img class="img-button btn-status-on" style="cursor: pointer" src="images/icon_status_green_light.gif" />&nbsp;<img class="img-button" src= "images/icon_status_red.gif" />';
    }
  };
  
  config.cm = new Ext.grid.ColumnModel([
    {header: '<?php echo $osC_Language->get('table_heading_sms_template_name'); ?>', dataIndex: 'sms_templates_name', width: 200},
    {id: 'sms_templates_title', header: '<?php echo $osC_Language->get('table_heading_sms_title'); ?>', width: 300, dataIndex: 'sms_title'},
    {header: '<?php echo $osC_Language->get('table_heading_sms_template_status'); ?>', dataIndex: 'sms_templates_status', width: 80, align: 'center', renderer: renderPublish},
    config.rowActions
  ]);
  config.autoExpandColumn = 'sms_templates_title';
    
  Toc.sms_templates.SmsTemplatesGrid.superclass.constructor.call(this, config);
};

Ext.extend(Toc.sms_templates.SmsTemplatesGrid, Ext.grid.GridPanel, {

  showSmsTemplatesDialog: function(record) {
    var dlg = this.owner.createSmsTemplatesDialog(record.get('sms_templates_name'));
    
    dlg.on('saveSuccess', function() {
      this.onRefresh();
    }, this);
    
    dlg.show(record);
  },
  
  onRefresh: function() {
    this.getStore().reload();
  },
  
  onRowAction: function(grid, record, action, row, col) {
    switch(action) {
      case 'icon-edit-record':
        this.showSmsTemplatesDialog(record);
        break;
    }
  },  
  
  onClick: function(e, target) {
    var t = e.getTarget();
    var v = this.view;
    var row = v.findRowIndex(t);
    var action = false;
  
    if (row !== false) {
      var btn = e.getTarget(".img-button");
      
      if (btn) {
        action = btn.className.replace(/img-button btn-/, '').trim();
      }

      if (action != 'img-button') {
        var smsTemplatesId = this.getStore().getAt(row).get('sms_templates_id');
        var module = 'setStatus';
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            flag = (action == 'status-on') ? 1 : 0;
            this.onAction(module, smsTemplatesId, flag);
            break;
        }
      }
    }
  },
  
  onAction: function(action, smsTemplatesId, flag) {
    Ext.Ajax.request({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'sms_templates',
        action: action,
        sms_templates_id: smsTemplatesId,
        flag: flag
      },
      callback: function(options, success, response) {
        result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          store.getById(smsTemplatesId).set('sms_templates_status', flag);
          store.commitChanges();
          
          this.owner.app.showNotification({title: TocLanguage.msgSuccessTitle, html: result.feedback});
        }
        else
          this.owner.app.showNotification({title: TocLanguage.msgSuccessTitle, html: result.feedback});
      },
      scope: this
    });
  }
});