<?php
/*
  $Id: sms_templates_dialog.php $
  $module: sanapayamak Persian TomatoCart Module $
  $author: Ali Masooumi $
*/

?>

Toc.sms_templates.SmsTemplatesDialog = function(config) {

  config = config || {};
  
  config.id = 'sms_templatesDialog-win';
  config.layout = 'fit';
  config.width = 720;
  config.height = 450;
  config.modal = true;
  config.iconCls = 'icon-sms_templates-win';
  config.items = this.buildForm();
  
  config.buttons = [
    {
      text:TocLanguage.btnSave,
      handler: function(){
        this.submitForm();
      },
      scope:this
    },
    {
      text: TocLanguage.btnClose,
      handler: function(){
        this.close();
      },
      scope:this
    }
  ];

  this.addEvents({'saveSuccess' : true});  
  
  Toc.sms_templates.SmsTemplatesDialog.superclass.constructor.call(this, config);
}

Ext.extend(Toc.sms_templates.SmsTemplatesDialog, Ext.Window, {

  show: function (record) {
    smsTemplateId = record.get('sms_templates_id');
    this.frmSmsTemplate.form.baseParams['sms_templates_id'] = smsTemplateId;
    
    this.frmSmsTemplate.load({
      url: Toc.CONF.CONN_URL,
      params:{
        module: 'sms_templates',
        action: 'load_sms_template',
        sms_templates_id: smsTemplateId
      },
      success: function(form, action) {
        this.dsVariables.baseParams['sms_templates_name'] = record.get('sms_templates_name');
        this.dsVariables.load();
        
        Toc.sms_templates.SmsTemplatesDialog.superclass.show.call(this);
      },
      failure: function(form, action) {
        Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
      }, 
      scope: this
    });
    
    Toc.sms_templates.SmsTemplatesDialog.superclass.show.call(this);
  },
  
  getDataPanel: function() {
    this.pnlData = new Ext.Panel({ 
      region: 'north',
      title: '<?php echo $osC_Language->get('heading_title_data'); ?>',
      labelWidth: 150,
      autoHeight: true,
      layout: 'form',
      defaults: {
        style: 'padding: 3px',
        anchor: '97%'
      },
      items: [                           
        { 
          labelSeparator: ' ',
          xtype: 'textfield', 
          fieldLabel: '<?php echo $osC_Language->get('field_sms_templates_name'); ?>', 
          name: 'sms_templates_name', 
          readOnly: true
        },
        {
          layout: 'column',
          border: false,
          items:[
            {
              width: 280,
              layout: 'form',
              labelSeparator: ' ',
              border: false,
              items:[
                {fieldLabel: '<?php echo $osC_Language->get('field_sms_templates_status'); ?>', boxLabel: '<?php echo $osC_Language->get('status_enabled'); ?>' , name: 'sms_templates_status', xtype:'radio', inputValue: '1'}
              ]
            },
            {
              width: 120,
              layout: 'form',
              border: false,
              items: [
                {hideLabel: true, boxLabel: '<?php echo $osC_Language->get('status_disabled'); ?>', xtype:'radio', name: 'sms_templates_status', inputValue: '0'}
              ]
            }
          ]
        }
      ]
    });
    
    return this.pnlData;
  },  
  
  getContentPanel: function() {
    this.tabLanguage = new Ext.TabPanel({
       region: 'center',
       defaults:{
         hideMode:'offsets'
       },
       activeTab: 0,
       deferredRender: false
    });  
    
    this.dsVariables = new Ext.data.Store({
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'sms_templates', 
        action: 'get_variables'
      },
      reader: new Ext.data.JsonReader({
        root: Toc.CONF.JSON_READER_ROOT,
        fields: ['id', 'value']
      })                                                                        
    });
    
    <?php
      foreach ($osC_Language->getAll() as $l) {
      
        echo 'this.pnlLang' . $l['id'] . ' = new Ext.Panel({
          labelWidth: 150,
          title:\'' . $l['name'] . '\',
          iconCls: \'icon-' . $l['country_iso'] . '-win\',
          layout: \'form\',
          labelSeparator: \' \',
          style: \'padding: 6px\',
          items: [
            {
              xtype: \'textfield\', 
              fieldLabel: \'' . $osC_Language->get('field_sms_title') . '\', 
              name: \'sms_title[' . $l['id'] . ']\', 
              id: \'title[' . $l['id'] . ']\', 
              allowBlank: false,
              width: 520
            },
            {
              layout: \'column\',
              border: false,
              items:[
                {
                  width: 560,
                  layout: \'form\',
                  labelSeparator: \' \',
                  border: false,
                  items:[
                    {
                      fieldLabel: \'' . $osC_Language->get('field_variables') . '\', 
                      name: \'variable[' . $l['id'] . ']\', 
                      id: \'sms-template-variables' . $l['id'] . '\', 
                      xtype: \'combo\', 
                      store: this.dsVariables, 
                      displayField: \'value\', 
                      valueField: \'value\', 
                      editable: false, 
                      triggerAction: \'all\', 
                      width: 300
                    }
                  ]
                },
                {
                  width: 80,
                  layout: \'form\',
                  border: false,
                  items: [
                    { 
                      xtype: \'button\', 
                      id: \'btn-insert-variables-'.$l['id'].'\', 
                      text: \'' . $osC_Language->get('button_insert') . '\', 
                      handler: function(){
                        this.insertVariable(' . $l['id'] . ');
                      },
                      scope: this
                    }
                  ]
                }
              ]
            },
            {
              xtype: \'htmleditor\', 
              fieldLabel: \'' . $osC_Language->get('field_sms_content') . '\', 
              name: \'sms_content[' . $l['id'] . ']\', 
              id: \'sms-template-content' . $l['id'] . '\',
              height: \'auto\',
              width: 520,
              listeners: {
                editmodechange: this.onEditModeChange
              }
            }
          ]
        });
        
        this.tabLanguage.add(this.pnlLang' . $l['id'] . ');
        ';
      }
    ?>
    
    return this.tabLanguage;
  },
  
  onEditModeChange: function(htmlEditor, sourceEdit) {
    var code = htmlEditor.getId().toString().substr(22);
    var btn = Ext.getCmp('btn-insert-variables-'+ code);
    
    if (sourceEdit === true) {
      btn.disable();
    } else {
      btn.enable();
    }
  },
  
  insertVariable: function(id) {
     var variable = Ext.getCmp('sms-template-variables'+ id).getValue();
     
     var editor = Ext.getCmp('sms-template-content'+ id);
     editor.focus(); 
     editor.insertAtCursor(variable);
  },

  buildForm: function() {
    this.frmSmsTemplate = new Ext.FormPanel({
      layout: 'border',
      width: 700,
      border: false,
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'sms_templates',
        action: 'save_sms_template'
      }, 
      items: [this.getDataPanel(), this.getContentPanel()]
    });
    
    return this.frmSmsTemplate;    
  },
  
  submitForm : function() {
    this.frmSmsTemplate.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        this.fireEvent('saveSuccess', action.result.feedback); 
        this.close();
      },
      failure: function(form, action) {
        if (action.failureType != 'client') {         
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);      
        }         
      },
      scope: this
    });   
  }
});