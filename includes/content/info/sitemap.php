<?php
/*
  $Id: sitemap.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd;  Copyright (c) 2006 osCommerce

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  require_once(realpath(dirname(__FILE__) . '/../../') . '/classes/articles.php');
  require_once(realpath(dirname(__FILE__) . '/../../') . '/classes/faqs.php');

  class osC_Info_Sitemap extends osC_Template {

/* Private variables */

    var $_module = 'sitemap',
        $_group = 'info',
        $_page_title,
        $_page_contents = 'info_sitemap.php',
        $_page_image = 'table_background_specials.gif';

/* Class constructor */

    function osC_Info_Sitemap() {
      global $osC_Services, $osC_Language, $breadcrumb, $Qinformation_listing, $articles_categories, $Qfaqs_listing;

      $this->_page_title = $osC_Language->get('info_sitemap_heading');
      
      //create the article instance to get the articles in each categories
      $articles = new toC_Articles();
      
      //get the articles listing for information category
      $Qinformation_listing = $articles->getListing(1);
      
      //get articles categories including the articles
      $articles_categories = $articles->getCategoriesListing();
      
      //get the faqs listing
      $faqs = new toC_Faqs();
      $Qfaqs_listing = $faqs->getListing();

      if ($osC_Services->isStarted('breadcrumb')) {
        $breadcrumb->add($osC_Language->get('breadcrumb_sitemap'), osc_href_link(FILENAME_INFO, $this->_module));
      }
    }
  }
?>
