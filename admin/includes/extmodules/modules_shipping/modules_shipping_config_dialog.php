<?php
/*
  $Id: modules_shipping_config_dialog.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>

Toc.modules_shipping.ShippingModuleConfigDialog = function(config) {
  
  config = config || {};

  config.id = 'modules_shipping-dialog-win';
  config.width = 440;
  config.height = 400;
  config.modal = true;
  config.iconCls = 'icon-modules_shipping-win';
  config.layout = 'border';
  config.items = this.buildForm(config.code);
  
  config.buttons = [
    {
      text:TocLanguage.btnSave,
      handler: function(){
        this.submitForm();
      },
      scope:this
    },{
      text: TocLanguage.btnClose,
      handler: function(){
        this.close();
      },
      scope:this
    }
  ];

  this.addEvents({'saveSuccess' : true});  
  
  Toc.modules_shipping.ShippingModuleConfigDialog.superclass.constructor.call(this, config);
}

Ext.extend(Toc.modules_shipping.ShippingModuleConfigDialog, Ext.Window, {
  
  buildForm: function(code) {
    this.moduleForm = new Ext.form.FormPanel({ 
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'modules_shipping',
        action: 'save'
      }, 
      region: 'center',
      layoutConfig: {
        labelSeparator: ''
      },
      labelAlign: 'top',
      autoScroll: true,
      defaults: {
        anchor: '90%'
      }
    });  
    
    this.requestForm(code);
    
    return this.moduleForm;
  },

  requestForm: function(code) {
    Ext.Ajax.request({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'modules_shipping',
        action: 'get_configuration_options',
        code: code
      },
      callback: function(options, success, response) {
        fields = Ext.decode(response.responseText);

        Ext.each(fields, function(field, i) {
          if(field.type == 'textfield'){
            this.moduleForm.add(
              new Ext.form.TextField({
                fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
                name: field.name,
                value: field.value
              })
            );
          } else if(field.type == 'textarea'){
            this.moduleForm.add(
              new Ext.form.TextArea({
                fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
                name: field.name,
                value: field.value
              })
            );
          } else if(field.type == 'combobox'){
            combo = new Ext.form.ComboBox({
              fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
              name: field.name,
              hiddenName: field.name,
              store: new Ext.data.SimpleStore({
                  fields: [{name: 'id', mapping: 'id'}, {name: 'text', mapping: 'text'}],
                  data : field.values
              }),
              displayField: 'text',
              valueField: 'id',
              mode: 'local',
              triggerAction: 'all',
              editable: false,
              allowblank: false,
              value: field.value
            });
              
            this.moduleForm.add(combo);
          } else if (field.type == 'multiselect') {
            var multiS = new Ext.ux.Andrie.Select(Ext.applyIf({
              fieldLabel: '<b>' + field.title + '</b><br/>' + field.description,
              multiSelect:true,
              minLength:2
            }, {
              store: new Ext.data.SimpleStore({
                  fields: [{name: 'id', mapping: 'id'}, {name: 'text', mapping: 'text'}],
                  data : field.values
              }),
              valueField:'id',
              displayField:'text',
              triggerAction:'all',
              mode:'local'
            }));
            
            var multiSvalue = new Ext.form.TextField({
              name: field.name,
              readOnly:true,
              value: field.value
            });
            
            multiS.on('change', function(){
              multiSvalue.setValue(this.getValue());
            }, multiS);
            
            this.moduleForm.add(multiS);
            this.moduleForm.add(multiSvalue);
          } else if (field.type == 'usps_checkbox') {
            var selected = field.value.split(',');
            Ext.each(field.values, function(value, i){
              var hideLabel = (i != 0) ? true : false;
              
              var checked = false;
             
              for (var j = 0; j < selected.length; j++) {
                if (selected[j] == value.id) {
                  checked = true;
                }
              }  
              
              var checkBox = new Ext.form.Checkbox({
                fieldLabel: '<b>' + field.title + '</b><br />' + field.description,
                name: field.name,
                boxLabel: value.text,
                inputValue: value.id,
                hideLabel: hideLabel,
                checked : checked
              });
              
              this.moduleForm.add(checkBox);
            }, this);
          }
        },this);
        
        this.doLayout();
      },
      scope: this
    });
  },

  submitForm : function() {
    this.moduleForm.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
        this.fireEvent('saveSuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        if(action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });   
  }
});