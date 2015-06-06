<?php
/*
  $Id:  new_guestbook.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

?>
  <h1><?php echo $osC_Template->getPageTitle(); ?></h1>
   
  <?php 
    if ($messageStack->size('guestbook') > 0) {
      echo $messageStack->output('guestbook');
    }
  ?>   
  
  <div class="moduleBox">
    <h6><?php echo $osC_Language->get('guestbook_new_heading'); ?></h6>
    
    <div class="content">
      <form name="guestbook_edit" action="<?php echo osc_href_link(FILENAME_INFO, 'guestbook=save'); ?>" method="post">
        <ol> 
          <li><?php echo osc_draw_label($osC_Language->get('field_title'), 'title', null, true) . osc_draw_input_field('title', null);  ?></li>
          <li><?php echo osc_draw_label($osC_Language->get('field_email'), 'email', null, true) . osc_draw_input_field('email', null, 'dir="ltr"');  ?></li>  
          <li><?php echo osc_draw_label($osC_Language->get('field_url'), 'url') . osc_draw_input_field('url', null, 'dir="ltr"');  ?></li>  
          <li><?php echo osc_draw_label($osC_Language->get('field_content'), 'content', null, true) . osc_draw_textarea_field('content', '', 29);  ?></li>
          <?php if( ACTIVATE_CAPTCHA == '1') {?>
      <li class="clearfix captcha" style="height:130px !important;">
        <table width="200" border="0">
          <tr>
            <td>&nbsp;</td>
            <td><?php echo osc_image(osc_href_link(FILENAME_INFO, 'contact=show_captcha', 'AUTO', true, false), $osC_Language->get('captcha_image_title'), 215, 80, 'id="captcha-code"'); ?></td>
            <td>&nbsp;</td>
          </tr>
        <tr>
                <td class="clearfix"><?php echo osc_draw_label($osC_Language->get('enter_captcha_code'), 'captcha_code', null, true); ?></td>
                <td><?php echo osc_draw_input_field('captcha_code', '', 'size="22" dir="ltr"'); ?></td>
                <td><?php echo osc_link_object(osc_href_link('#'), osc_image('ext/securimage/images/refresh.png', $osC_Language->get('refresh_captcha_image_title')), 'id="refresh-captcha-code"'); ?></td>
		</tr>
        </table>		
      </li>
          <?php } ?>
        </ol>
        
        <div class="submitFormButtons">
          <span style="float: left"><?php echo osc_draw_image_submit_button('button_continue.gif', $osC_Language->get('button_continue')) ?></span>
          
            <?php echo osc_link_object(osc_href_link(FILENAME_INFO, 'guestbook'), osc_draw_image_button('button_back.gif', $osC_Language->get('button_back'))); ?>
        </div>
      </form>
    </div>
  </div>
  
  <?php if( ACTIVATE_CAPTCHA == '1') {?>
  <script type="text/javascript">
    $('refresh-captcha-code').addEvent('click', function(e) {
      e.stop();
      
      var guestbookController = '<?php echo osc_href_link(FILENAME_INFO, 'guestbook=show_captcha', 'AUTO', true, false); ?>';
      var captchaImgSrc = guestbookController + '&' + Math.random();
            
      $('captcha-code').setProperty('src', captchaImgSrc);
    });
  </script>
  <?php } ?>