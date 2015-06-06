<?php
 /*
  $Id: toc_pdf.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
  */
  
  require_once('ext/tcpdf/tcpdf.php');  
  
  require_once('includes/classes/currencies.php');
  require_once('includes/classes/directory_listing.php');

  define('TOC_PDF_POS_START_X', 70);
  define('TOC_PDF_POS_START_Y', 36);
  define('TOC_PDF_LOGO_UPPER_LEFT_CORNER_X', 10);
  define('TOC_PDF_LOGO_UPPER_LEFT_CORNER_Y', 10);
  define('TOC_PDF_LOGO_WIDTH', 70);
  define('TOC_PDF_LOGO_HEIGHT', 20);
  
  define('TOC_PDF_POS_STORE_ADDRESS_Y', 44);  
  define('TOC_PDF_POS_ADDRESS_INFO_Y', TOC_PDF_POS_STORE_ADDRESS_Y + 25);

  define('TOC_PDF_POS_CONTENT_Y', (TOC_PDF_POS_ADDRESS_INFO_Y + 20));
  define('TOC_PDF_POS_HEADING_TITLE_Y', 35);
  define('TOC_PDF_POS_DOC_INFO_FIELD_Y', 13);
  define('TOC_PDF_POS_DOC_INFO_VALUE_Y', 13);
  define('TOC_PDF_POS_PRODUCTS_TABLE_HEADING_Y', (TOC_PDF_POS_CONTENT_Y + 20));
  define('TOC_PDF_POS_PRODUCTS_TABLE_CONTENT_Y', (TOC_PDF_POS_PRODUCTS_TABLE_HEADING_Y + 6));
  
  define('TOC_PDF_FONT', 'nazanin');
  define('TOC_PDF_FONT_B', 'bnazanin');
  define('TOC_PDF_HEADER_BILLING_INFO_FONT_SIZE', 12);
  define('TOC_PDF_HEADER_STORE_ADDRESS_FONT_SIZE', 10);
  define('TOC_PDF_FOOTER_PAGEING_FONT_SIZE', 8);
  define('TOC_PDF_TOTAL_FONT_SIZE', 10);
  define('TOC_PDF_TITLE_FONT_SIZE', 14);
  define('TOC_PDF_FIELD_DATE_PURCHASE_FONT_SIZE', 10);
  define('TOC_PDF_TABLE_HEADING_FONT_SIZE', 10);
  define('TOC_PDF_TABLE_CONTENT_FONT_SIZE', 9);
  define('TOC_PDF_TABLE_CONTENT_HEIGHT', 5);
  define('TOC_PDF_TABLE_PRODUCT_VARIANT_FONT_SIZE', 8);
  define('TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE', 10);
  define('TOC_PDF_SHIP_TO_TITLE_FONT_SIZE', 11);
  
  /*
   *  Class TOCPDF 
   */
  class TOCPDF extends TCPDF {

    var $_customer_info = array();

    function setCustomerInfo($customer_info) {
      $this->_customer_info = $customer_info;
    }
    
    function getOriginalLogo() {
      $osC_DirectoryListing = new osC_DirectoryListing(DIR_WS_IMAGES);
      $osC_DirectoryListing->setIncludeDirectories(false);
      $files = $osC_DirectoryListing->getFiles();
  
      foreach ( $files as $file ) {
        $filename = explode(".", $file['name']);
  
        if($filename[0] == 'logo_originals'){
          return DIR_WS_IMAGES . 'logo_originals.' . $filename[1];
        }
      }
  
      return false;
    }    
    
    function Header() {
      global $osC_Template;
      
      //Logo
      $logo = $this->getOriginalLogo();
      $logo = ($logo === false) ? (DIR_FS_CATALOG . DIR_WS_IMAGES . 'store_logo.jpg') : DIR_FS_CATALOG . $logo;
      $this->Image($logo, TOC_PDF_LOGO_UPPER_LEFT_CORNER_X, TOC_PDF_LOGO_UPPER_LEFT_CORNER_Y, TOC_PDF_LOGO_WIDTH, TOC_PDF_LOGO_HEIGHT);
      
      //Line $x1, $y1, $x2, $y2
      $this->line(10, 35, 200, 35,array('dash' => 1));
      $this->line(10, 43, 200, 43,array('dash' => 1));
      $this->line(10, 68, 200, 68,array('dash' => 1));

      //Store Address
      $this->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_HEADER_BILLING_INFO_FONT_SIZE);
      $this->SetY(TOC_PDF_POS_STORE_ADDRESS_Y);
	  $this->Cell(8, 6, 'مشخصات فروشنده', 0, 0, 'R', 0);		  
      $this->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_HEADER_STORE_ADDRESS_FONT_SIZE);
      $this->SetY(TOC_PDF_POS_STORE_ADDRESS_Y+8);
      $this->MultiCell(80, 4, STORE_NAME_ADDRESS, 0 ,'R');
	  
      //Billing Information
      $this->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_HEADER_BILLING_INFO_FONT_SIZE);
      $this->SetY(TOC_PDF_POS_ADDRESS_INFO_Y);
	  $this->Cell(8, 6, 'مشخصات خریدار', 0, 0, 'R', 0);
      $this->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE);		  
      $this->SetY(TOC_PDF_POS_ADDRESS_INFO_Y+7);	  
	  $this->Cell(10, 6, 'نام :', 0, 0, 'R', 0);
	  
      $this->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE);			  
	  $this->Cell(30, 6, $this->_customer_info['name'], 0, 0, 'R', 0);
      $this->SetY(TOC_PDF_POS_ADDRESS_INFO_Y+7);
      $this->SetX(150);
      $this->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE);	 	  
	  $this->Cell(20, 6, 'شماره تماس :', 0, 0, 'R', 0);
      $this->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE);	 	  
	  $this->Cell(20, 6, $this->_customer_info['telephone'], 0, 0, 'R', 0);	
	  
	  
      $this->SetY(TOC_PDF_POS_ADDRESS_INFO_Y+13);
      $this->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE);	  
	  $this->Cell(10, 6, 'آدرس :', 0, 0, 'R', 0);
      $this->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE);			  
	  $this->Cell(100, 6, "استان : " . $this->_customer_info['state'] . " - شهر : " . $this->_customer_info['city'] . " - " . $this->_customer_info['street_address'], 0, 0, 'R', 0);		  
      $this->SetY(TOC_PDF_POS_ADDRESS_INFO_Y+13);
      $this->SetX(150);	  
      $this->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE);	  
	  $this->Cell(20, 6, 'کد پستی :', 0, 0, 'R', 0);
      $this->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_SHIP_TO_ADDRESS_FONT_SIZE);	  
	  $this->Cell(20, 6, $this->_customer_info['postcode'], 0, 0, 'R', 0);	  	  


	  
    }
    
    function Footer() {
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Set font
      $this->SetFont(TOC_PDF_FONT, 'I', TOC_PDF_FOOTER_PAGEING_FONT_SIZE);
      // Page number
      $this->Cell(0, 15, 'صفحه: ' . $this->getAliasNumPage() . ' از ' . $this->getAliasNbPages(), 0, 0, 'L');
    }
  }
?>