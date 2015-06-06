<?php
/*
  $Id: credit_slip.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com
  http://www.tomatoshop.ir  Persian Tomatocart v1.1.8.6 / Khordad 1394
  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/
  require_once('includes/classes/credit_slip.php');
  
class toC_Credit_Slip_PDF {
  
    var $_pdf = null,
        $_title = null,
        $_credit_slip = null;
  
    function toC_Credit_Slip_PDF() {
      global $osC_Customer;
        
      $this->_credit_slip = new toC_Credit_Slip($_GET['credit_slip_id']);
      $customer_info = $this->_credit_slip->getCustomerInfo();
          
      $logged_in_customers_id = $osC_Customer->getID();
      if ($logged_in_customers_id != $customer_info['id']) {
          die ('You are not allowed to print this credit slip');
      }
      
      $this->_pdf = new TOCPDF('P', 'mm', 'A4', true, 'UTF-8');
      $this->_pdf->SetCreator('TomatoShop');
      $this->_pdf->SetAuthor('TomatoShop');
      $this->_pdf->SetTitle('Credit Slip');
      $this->_pdf->SetSubject($credit_slip_id . ': ' . $address_info['name']);
      $this->_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
      
      $this->_pdf->setCustomerInfo($customer_info);
	  
	  // set some language dependent data:
	  $lg = Array();
	  $lg['a_meta_charset'] = 'UTF-8';
	  $lg['a_meta_dir'] = 'rtl';
	  $lg['a_meta_language'] = 'fa';
	  $lg['w_page'] = 'page';

	  // set some language-dependent strings (optional)
	  $this->_pdf->setLanguageArray($lg);	  
    }
    
    function render () {
      global $osC_Language;
      
      //New Page
      $this->_pdf->AddPage(); 
  
      //Title
      $this->_pdf->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_TITLE_FONT_SIZE);
      $this->_pdf->SetY(TOC_PDF_POS_HEADING_TITLE_Y);
      $this->_pdf->MultiCell(0, 4, $osC_Language->get('credit_slip_heading_title'), 0, 'C');
      
      //Date purchase & order ID field title
      $this->_pdf->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_FIELD_DATE_PURCHASE_FONT_SIZE);
      $this->_pdf->SetY(TOC_PDF_POS_DOC_INFO_FIELD_Y-3);
      $this->_pdf->SetX(135);
      $this->_pdf->MultiCell(55, 4, $osC_Language->get('operation_heading_credit_slip_id') . "\n" . $osC_Language->get('operation_heading_credit_slip_date') . "\n" . $osC_Language->get('operation_heading_order_id') . "\n" . $osC_Language->get('operation_heading_order_date'), 0, 'R');

      //Date purchase & order ID field value
      $this->_pdf->SetFont(TOC_PDF_FONT, '', TOC_PDF_FIELD_DATE_PURCHASE_FONT_SIZE);
      $this->_pdf->SetY(TOC_PDF_POS_DOC_INFO_VALUE_Y-3);
      $this->_pdf->SetX(150);
      $this->_pdf->MultiCell(40, 4, $this->_credit_slip->getCreditSlipId()  . "\n" . osC_DateTime::getShort($this->_credit_slip->getDateAdded()) . "\n" . $this->_credit_slip->getOrdersId() . "\n" . osC_DateTime::getShort($this->_credit_slip->getDatePurchased()), 0, 'L');

      //Products
      $this->_pdf->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_TABLE_HEADING_FONT_SIZE);
      $this->_pdf->SetY(TOC_PDF_POS_PRODUCTS_TABLE_HEADING_Y);
      $this->_pdf->Cell(10, 6, '', 'TB', 0, 'C', 0);
      $this->_pdf->Cell(80, 6, $osC_Language->get('heading_products_name'), 'TB', 0, 'C', 0);
      $this->_pdf->Cell(37, 6,  $osC_Language->get('heading_products_quantity'), 'TB', 0, 'C', 0);
      $this->_pdf->Cell(32, 6, $osC_Language->get('heading_products_price'), 'TB', 0, 'C', 0);
      $this->_pdf->Cell(32, 6, $osC_Language->get('heading_products_total'), 'TB', 0, 'C', 0);
      $this->_pdf->Ln();
      
      $i = 0;
      $y_table_position = TOC_PDF_POS_PRODUCTS_TABLE_CONTENT_Y;
      $osC_Currencies = new osC_Currencies();
      
      foreach ($this->_credit_slip->getProducts() as $products) {
        $rowspan = 1;
        
        //Pos
        $this->_pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TABLE_CONTENT_FONT_SIZE);
        $this->_pdf->SetY($y_table_position);
        $this->_pdf->MultiCell(8, 4, ($i + 1), 0, 'C');
      
        //Product
        $this->_pdf->SetY($y_table_position);
        $this->_pdf->SetX(30);
        
        $product_info = $products['name'];
        if (strlen($products['name']) > 70) {
          $rowspan = 2;
        }
        
        if ( $products['type'] == PRODUCT_TYPE_GIFT_CERTIFICATE ) {
          $product_info .= "\n" . '   -' . $osC_Language->get('senders_name') . ': ' . $products['senders_name'];
          
          if ($products['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL) {
            $product_info .= "\n" . '   -' . $osC_Language->get('senders_email') . ': ' . $products['senders_email'];
            $rowspan++;
          }
          
          $product_info .= "\n" . '   -' . $osC_Language->get('recipients_name') . ': ' . $products['recipients_name'];
          
          if ($products['gift_certificates_type'] == GIFT_CERTIFICATE_TYPE_EMAIL) {
            $product_info .= "\n" . '   -' . $osC_Language->get('recipients_email') . ': ' . $products['recipients_email'];
            $rowspan++;
          }
          
          $rowspan += 3;
          $product_info .= "\n" . '   -' . $osC_Language->get('messages') . ': ' . $products['messages'];
        }
        
        if (isset( $products['variants'] ) && ( sizeof( $products['variants'] ) > 0)) {
          foreach ( $products['variants'] as $variant ) {
            $product_info .=  "\n" . $variant['groups_name'] . ": " . $variant['values_name'];
            $rowspan++;
          } 
        } 
        $this->_pdf->MultiCell(80, 4, $product_info, 0, 'R');          
  
        //Quantity
        $this->_pdf->SetY($y_table_position);
        $this->_pdf->SetX( 113 );
        $this->_pdf->MultiCell(10, 4, $products['qty'], 0, 'C');
        
        //Price
        $this->_pdf->SetY($y_table_position);
        $this->_pdf->SetX(143);
        $price = $osC_Currencies->displayPriceWithTaxRate($products['final_price'], $products['tax'], 1, $this->_credit_slip->getCurrency(), $this->_credit_slip->getCurrencyValue());
        $price = str_replace('&nbsp;',' ',$price);
        $this->_pdf->MultiCell(20, 4, $price, 0, 'C');
        
        //Total
        $this->_pdf->SetY($y_table_position);
        $this->_pdf->SetX(173);
        $total = $osC_Currencies->displayPriceWithTaxRate($products['final_price'], $products['tax'], $products['qty'], $this->_credit_slip->getCurrency(), $this->_credit_slip->getCurrencyValue());
        $total = str_replace('&nbsp;', ' ', $total);
        $this->_pdf->MultiCell(25, 4, $total, 0, 'C');
        
        $y_table_position += $rowspan * TOC_PDF_TABLE_CONTENT_HEIGHT;
        
        //products list exceed page height, create a new page
        if (($y_table_position - TOC_PDF_POS_CONTENT_Y - 6) > 160) { 
          $this->_pdf->AddPage();
          
          $y_table_position = TOC_PDF_POS_CONTENT_Y + 6;
          $this->_pdf->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_TABLE_HEADING_FONT_SIZE);
          $this->_pdf->SetY(TOC_PDF_POS_CONTENT_Y);
          $this->_pdf->Cell(10, 6, '', 'TB', 0, 'C', 0);
          $this->_pdf->Cell(80, 6, $osC_Language->get('heading_products_name'), 'TB', 0, 'C', 0);
          $this->_pdf->Cell(37, 6, $osC_Language->get('heading_products_quantity'), 'TB', 0, 'C', 0);
          $this->_pdf->Cell(32, 6, $osC_Language->get('heading_products_price'), 'TB', 0, 'C', 0);
          $this->_pdf->Cell(32, 6, $osC_Language->get('heading_products_total'), 'TB', 0, 'C', 0);
          $this->_pdf->Ln();
        }      
        $i++;
      }
      $this->_pdf->SetY($y_table_position + 1);
      $this->_pdf->Cell(191, 7, '', 'T', 0, 'C', 0);
    
      $y_table_position+= 4;
      $this->_pdf->SetFont(TOC_PDF_FONT_B, 'B', TOC_PDF_TOTAL_FONT_SIZE);
      $this->_pdf->SetY($y_table_position);
      $this->_pdf->SetX(40);
      $this->_pdf->MultiCell(120, 5, $osC_Language->get('refund_sub_total') . "\n". $osC_Language->get('refund_shipping') . "\n". $osC_Language->get('refund_handling') . "\n". $osC_Language->get('refund_total') . "\n", 0, 'L');

      $this->_pdf->SetFont(TOC_PDF_FONT, 'B', TOC_PDF_TOTAL_FONT_SIZE);	  
      $this->_pdf->SetY($y_table_position);
      $this->_pdf->SetX(165);
      $this->_pdf->MultiCell(40, 5, $osC_Currencies->format($this->_credit_slip->getSubTotal(), $this->_credit_slip->getCurrency(), $this->_credit_slip->getCurrencyValue()) . "\n" . $osC_Currencies->format($this->_credit_slip->getShippingFee(), $this->_credit_slip->getCurrency(), $this->_credit_slip->getCurrencyValue()) . "\n" . $osC_Currencies->format($this->_credit_slip->getHandlingFee(), $this->_credit_slip->getCurrency(), $this->_credit_slip->getCurrencyValue()) . "\n" . $osC_Currencies->format($this->_credit_slip->getTotal(), $this->_credit_slip->getCurrency(), $this->_credit_slip->getCurrencyValue()), 0, 'C');
      $this->_pdf->Output("Order.pdf", "I");
    }
  }
?>