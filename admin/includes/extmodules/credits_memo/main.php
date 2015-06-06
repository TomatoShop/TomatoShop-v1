<?php
/*
  $Id: main.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  echo 'Ext.namespace("Toc.credits_memo");';
  
  include('credits_memo_grid.php');
?>

Ext.override(TocDesktop.CreditsMemoWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('credits_memo-win');
     
    if (!win) {
      grd = new Toc.credits_memo.CreditsMemoGrid({owner: this});
      
      win = desktop.createWindow({
        id: 'credits_memo-win',
        title: '<?php echo $osC_Language->get('heading_title'); ?>',
        width: 850,
        height: 400,
        iconCls: 'icon-credits_memo-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  }
  
});