<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Sales Order Invoice PDF model
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Spranks_Packingslip_Model_Order_Pdf_Order extends Mage_Sales_Model_Order_Pdf_Abstract
{
	public $colors;
	public $encoding;
	public $margin;
	public $impressum;
	public $pagecounter;
	
//	
//	protected function insertTotals(&$page, $source){
//		$source->setOrder($source);
//        parent::insertTotals($page,$source);
//    }

	public function __construct()
	{
		parent::__construct();
		
		$this->encoding = 'UTF-8';
		
		$this->colors['black'] = new Zend_Pdf_Color_GrayScale(0);
		$this->colors['grey1'] = new Zend_Pdf_Color_GrayScale(0.9);
		
		$this->margin['left'] = 45;
		$this->margin['right'] = 540;
		
		$impressum = Mage::getConfig()->getNode('modules/Symmetrics_Impressum');
		
		if (is_object($impressum)) {
			if ($impressum->active == 'true') {
                $this->impressum = Mage::getModel('Symmetrics_Impressum_Block_Impressum')->getImpressumData();
			}
		}
		else {
			$this->impressum = false;
		}
	}
	
    public function getPdf($orders = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('order');

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
		
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
        
        $this->pagecounter = 1;

        foreach ($orders as $order) {
            $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
            $pdf->pages[] = $page;

            /* Add logo */
            $this->insertLogo($page, $order->getStore());
			
			/* add billing address */
			$this->y = 692;
            $this->insertShippingAddress($page, $order);

            /* add sender address */
            $this->y = 705;
            $this->insertSenderAddress($page);
            
            /* add header */
			$this->y = 592;
			$this->insertHeader($page, $order);

            /* 
             * add footer if the impressum module is 
             * installed and "insert footer" switch 
             * in configuration is enabled 
             * */
			if ($this->impressum && Mage::getStoreConfig('sales_pdf/invoice/showfooter') == 1) {
                $this->y = 110;
                $this->insertFooter($page);
			}
			
			/* add page counter */
			$this->y = 110;
			$this->insertPageCounter($page);
			
            /* add table header */
			$this->_setFontRegular($page, 9);
			$this->y = 562;
			$this->insertTableHeader($page);

            $this->y -=20;

            $position = 0;
            /* Add body */
            foreach ($order->getAllItems() as $item){
            	if ($item->getParentItem()) {
                    continue;
                }
                
                if ($this->y < 200) {
                    $page = $this->newPage(array());
                }

                $position++;
                /* Draw item */
                $page = $this->_drawItem($item, $page, $order, $position);				
			}

        }
		
		$this->insertNote($page);

        $this->_afterGetPdf();

        return $pdf;
    }
	
	protected function insertNote($page)
	{
		$this->Ln(15);
		$this->Ln(15);
		//$note1 = 'Wichtiger Hinweis:';
		//$note2 = 'Bitte senden Sie uns keine Ware unfrei zurück, wir müssen Ihnen sonst die entstandenen Gebühren';
		//$note3 = 'berechnen! Bezüglich der Kosten bei Rücksendungen verweisen wir dabei auf Punkt 4 unserer AGB.';
		//$note4 = 'Haben wir die Rücksendung zu tragen oder haben Sie Fragen zur Rücksendung, schreiben Sie eine';
		//$note5 = 'E-Mail an unseren Kundendienst unter xxx@xxx.de oder rufen Sie uns an unter';
		//$note6 = '0123456789.';
    	//$this->_setFontBold($page, 10);
    	//$page->drawText($note1, $this->margin['left'], $this->y, $this->encoding);
		//$this->Ln();
		//$page->drawText($note2, $this->margin['left'], $this->y, $this->encoding);
		//$this->Ln();
		//$page->drawText($note3, $this->margin['left'], $this->y, $this->encoding);
		///$this->Ln();
		//$page->drawText($note4, $this->margin['left'], $this->y, $this->encoding);
		//$this->Ln();
		//$page->drawText($note5, $this->margin['left'], $this->y, $this->encoding);
		//$this->Ln();
		//$page->drawText($note6, $this->margin['left'], $this->y, $this->encoding);
		//$this->_setFontRegular($page);
	}

    protected function insertLogo(&$page, $store = null) 
    {
    	$maxwidth = 130;
    	$maxheight = 80;
    	
        $image = Mage::getStoreConfig('sales/identity/logo', $store);
        if ($image and file_exists(Mage::getStoreConfig('system/filesystem/media', $store) . '/sales/store/logo/' . $image)) {
            $image = Mage::getStoreConfig('system/filesystem/media', $store) . '/sales/store/logo/' . $image;
            
            $size = getimagesize($image);
            
            $width = $size[0];
            $height = $size[1];
            
            if ($width > $height) {
            	$ratio = $width / $height;
            }
            elseif ($height > $width) {
            	$ratio = $height / $width;
            }
            else {
            	$ratio = 1;
            }
            
            if ($height > $maxheight or $width > $maxwidth) {
            	if ($height > $maxheight) {
					$height = $maxheight;
					$width = round($maxheight * $ratio);
				}
         		
				if ($width > $maxwidth) {
					$width = $maxheight;
					$height = round($maxwidth * $ratio);
				}
            }

            if (is_file($image)) {
                $image = Zend_Pdf_Image::imageWithPath($image);
                
                $logoPosition = Mage::getStoreConfig('sales/identity/logoposition', $store); 
                
                switch($logoPosition) {
                    case 'center':
                        $startLogoAt = $this->margin['left'] + ( ($this->margin['right'] - $this->margin['left']) / 2 ) - $width / 2;
                    break;
                    case 'right':
                        $startLogoAt = $this->margin['right'] - 200;
                    break;
                    default:
                        $startLogoAt = $this->margin['left'];
                }

                $position['x1'] = $startLogoAt + 40;
                $position['y1'] = 662;
                $position['x2'] = $position['x1'] + $width;
                $position['y2'] = $position['y1'] + $height;
               
                $page->drawImage($image, $position['x1'], $position['y1'], $position['x2'], $position['y2']);
            }
        }
    }

    protected function insertShippingAddress(&$page, $order)
    {
		$this->_setFontRegular($page, 9);
		
		$shipping = $this->_formatAddress($order->getShippingAddress()->format('pdf'));
		
		foreach ($shipping as $line) {
			$page->drawText(trim(strip_tags($line)), $this->margin['left'], $this->y, $this->encoding);
			$this->Ln(12);
		}
    }
    
    protected function _setFontRegular($object, $size = 10)
    {
    	$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $object->setFont($font, $size);
        return $font;
    }
    
    protected function Ln($height=15)
	{
    	$this->y -= $height;
    }
    
    protected function insertSenderAddress($page) 
    {
        if($senderAddress = Mage::getStoreConfig('sales_pdf/invoice/senderaddress')) {
            $this->_setFontRegular($page, 7);
            $page->drawText($senderAddress,  $this->margin['left'], $this->y, $this->encoding);
        }
        return;
    }
    
    protected function insertHeader(&$page, $order)
    {
    	$page->setFillColor($this->colors['black']);
    	
    	$mode = $this->getMode();
    	
    	$this->_setFontBold($page, 15);

    	//$page->drawText('Lieferschein', $this->margin['left'], $this->y, $this->encoding);

    	$this->_setFontRegular($page);    	
    	
    	$this->y += 34;
    	$rightoffset = 200;
    	
    	$page->drawText('Order', ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	$this->Ln();
    	$page->drawText(Mage::helper('invoicepdf')->__('Customer number:'), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	$this->Ln();
    	
    	$yPlus = 30;
    	
    	if(Mage::getStoreConfig('sales_pdf/invoice/showcustomerip')) {
        	$page->drawText(Mage::helper('invoicepdf')->__('Customer IP:'), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
        	$this->Ln();
        	$yPlus = 45;
    	}

    	$page->drawText('Order Date', ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	$this->Ln();
    	$page->drawText('Payment method:', ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	
    	//$this->y += $yPlus;
		$this->y += 45;
    	$rightoffset = 80;
    	$page->drawText($order->getRealOrderId(), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	$this->Ln();
    	
    	$prefix = Mage::getStoreConfig('sales_pdf/invoice/customeridprefix');

    	if (!empty($prefix)) {
			$customerid = $prefix.$order->getBillingAddress()->getCustomerId();	
    	}
    	else {
    		$customerid = $order->getBillingAddress()->getCustomerId();	
    	}
    	
    	$rightoffset = 30;

    	$font = $this->_setFontRegular($page, 10);
    	$page->drawText($customerid, ($this->margin['right'] - $rightoffset - $this->widthForStringUsingFontSize($customerid, $font, 10)), $this->y, $this->encoding);
    	$this->Ln();
    	if(Mage::getStoreConfig('sales_pdf/invoice/showcustomerip')) {
    		$customerIP = $order->getData('remote_ip');
        	$font = $this->_setFontRegular($page, 10);
        	$page->drawText($customerIP, ($this->margin['right'] - $rightoffset - $this->widthForStringUsingFontSize($customerIP, $font, 10)), $this->y, $this->encoding);
        	$this->Ln();
    	}
    	
    	$invoiceDate = Mage::helper('core')->formatDate($order->getCreatedAtDate(), 'medium', false);
    	$page->drawText($invoiceDate, ($this->margin['right'] - $rightoffset - $this->widthForStringUsingFontSize($invoiceDate, $font, 10)), $this->y, $this->encoding);

		// inserted by Simon Sprankel 2nd March 2010
		$paymentMethod = $order->getPayment()->getMethodInstance()->getTitle();
		
		$this->Ln();
		$page->drawText($paymentMethod, ($this->margin['right'] - $rightoffset - $this->widthForStringUsingFontSize($invoiceDate, $font, 10)), $this->y, $this->encoding);
    }

    protected function _setFontBold($object, $size = 10)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $object->setFont($font, $size);
        return $font;
    }
    
    protected function insertFooter(&$page) 
    {
		$page->setLineColor($this->colors['black']);
		$page->setLineWidth(0.5);
		$page->drawLine($this->margin['left'] - 20, $this->y - 5, $this->margin['right'] + 30, $this->y - 5);
		
		$this->Ln(15);
		$this->insertFooterAddress($page);

		$fields = array(
			'telephone' => Mage::helper('impressum')->__('Telephone:'),
			'fax' => Mage::helper('impressum')->__('Fax:'),
			'email' => Mage::helper('impressum')->__('E-Mail:'),
			'web' => Mage::helper('impressum')->__('Web:')
		);
		$this->insertFooterBlock($page, $fields, 70, 40);
		
		$fields = array(
			'bankname' => Mage::helper('impressum')->__('Bank name:'),
			'bankaccount' => Mage::helper('impressum')->__('Account:'),
			'bankcodenumber' => Mage::helper('impressum')->__('Bank number:'),
			'bankaccountowner' => Mage::helper('impressum')->__('Account owner:')
		);
		$this->insertFooterBlock($page, $fields, 215, 50);
		
		$fields = array(
			'taxnumber' => Mage::helper('impressum')->__('Tax number:'),
			'vatid' => Mage::helper('impressum')->__('VAT-ID:'),
			'hrb' => Mage::helper('impressum')->__('Register number:'),
			'ceo' => Mage::helper('impressum')->__('CEO:')
		);
		$this->insertFooterBlock($page, $fields, 355, 60);
    }
    
    protected function insertFooterAddress(&$page, $store = null)
    {    	
		$this->_setFontRegular($page, 7);
		$y = $this->y;
		foreach (explode("\n", Mage::getStoreConfig('sales/identity/address', $store)) as $value) {
			if ($value!=='') {
				$page->drawText(trim(strip_tags($value)), $this->margin['left'] - 20, $y, $this->encoding);
				$y -= 12;
			}
		}
    }
    
    protected function insertFooterBlock(&$page, $fields, $colposition = 0, $valadjust = 30)
    {
        $this->_setFontRegular($page, 7);
		$y = $this->y;

		$valposition = $colposition + $valadjust;
		
		if (is_array($fields)) {
			foreach ($fields as $field => $label) {
				if (empty($this->impressum[$field])) {
					continue;
				}
				$page->drawText($label , $this->margin['left'] + $colposition, $y, $this->encoding);
				$page->drawText( $this->impressum[$field], $this->margin['left'] + $valposition, $y, $this->encoding);
				$y -= 12;
			}
		}
    }
    
    protected function insertPageCounter(&$page)
    {
    	$font = $this->_setFontRegular($page, 9);
    	$page->drawText(Mage::helper('invoicepdf')->__('Page').' '.$this->pagecounter, $this->margin['right'] - 23 - $this->widthForStringUsingFontSize($this->pagecounter, $font, 9), $this->y, $this->encoding);
    }
    
    protected function insertTableHeader(&$page)
    {
		$page->setFillColor($this->colors['grey1']);
		$page->setLineColor($this->colors['grey1']);
		$page->setLineWidth(1);
		$page->drawRectangle($this->margin['left'], $this->y, $this->margin['right'], $this->y - 15);

		$page->setFillColor($this->colors['black']);
		$font = $this->_setFontRegular($page, 9);
		
		$this->y -= 11;
		$page->drawText(Mage::helper('invoicepdf')->__('Amount'), 		$this->margin['left'] + 3, 		$this->y, $this->encoding);
		$page->drawText(Mage::helper('invoicepdf')->__('No.'), 			$this->margin['left'] + 50, 	$this->y, $this->encoding);
		$page->drawText(Mage::helper('invoicepdf')->__('Description'), 	$this->margin['left'] + 120, 	$this->y, $this->encoding);
    }
    
    protected function _drawItem(Varien_Object $item, Zend_Pdf_Page $page, Mage_Sales_Model_Order $order, $position = 1)
    {
        $type = $item->getProductType();
        $renderer = $this->_getRenderer($type);
        $renderer->setOrder($order);
        $renderer->setItem($item);
        $renderer->setPdf($this);
        $renderer->setPage($page);
        $renderer->setRenderedModel($this);

        $renderer->draw($position);
		
        return $renderer->getPage();
    }
    
    public function drawLineBlocks(Zend_Pdf_Page $page, array $draw, array $pageSettings = array())
    {
        foreach ($draw as $itemsProp) {
            if (!isset($itemsProp['lines']) || !is_array($itemsProp['lines'])) {
                Mage::throwException(Mage::helper('sales')->__('Invalid draw line data. Please define "lines" array'));
            }
            $lines  = $itemsProp['lines'];
            $height = isset($itemsProp['height']) ? $itemsProp['height'] : 10;

            if (empty($itemsProp['shift'])) {
                $shift = 0;
                foreach ($lines as $line) {
                    $maxHeight = 0;
                    foreach ($line as $column) {
                        $lineSpacing = !empty($column['height']) ? $column['height'] : $height;
                        if (!is_array($column['text'])) {
                            $column['text'] = array($column['text']);
                        }
                        $top = 0;
                        foreach ($column['text'] as $part) {
                            $top += $lineSpacing;
                        }

                        $maxHeight = $top > $maxHeight ? $top : $maxHeight;
                    }
                    $shift += $maxHeight;
                }
                $itemsProp['shift'] = $shift;
            }

            if ($this->y - $itemsProp['shift'] < 200) {
                $page = $this->newPage($pageSettings);
            }

            foreach ($lines as $line) {
                $maxHeight = 0;
                foreach ($line as $column) {
                    $fontSize  = empty($column['font_size']) ? 7 : $column['font_size'];
                    if (!empty($column['font_file'])) {
                        $font = Zend_Pdf_Font::fontWithPath($column['font_file']);
                        $page->setFont($font);
                    }
                    else {
                        $fontStyle = empty($column['font']) ? 'regular' : $column['font'];
                        switch ($fontStyle) {
                            case 'bold':
                                $font = $this->_setFontBold($page, $fontSize);
                                break;
                            case 'italic':
                                $font = $this->_setFontItalic($page, $fontSize);
                                break;
                            default:
                                $font = $this->_setFontRegular($page, $fontSize);
                                break;
                        }
                    }

                    if (!is_array($column['text'])) {
                        $column['text'] = array($column['text']);
                    }

                    $lineSpacing = !empty($column['height']) ? $column['height'] : $height;
                    $top = 0;
                    foreach ($column['text'] as $part) {
                        $feed = $column['feed'];
                        $textAlign = empty($column['align']) ? 'left' : $column['align'];
                        $width = empty($column['width']) ? 0 : $column['width'];
                        switch ($textAlign) {
                            case 'right':
                                if ($width) {
                                    $feed = $this->getAlignRight($part, $feed, $width, $font, $fontSize);
                                }
                                else {
                                    $feed = $feed - $this->widthForStringUsingFontSize($part, $font, $fontSize);
                                }
                                break;
                            case 'center':
                                if ($width) {
                                    $feed = $this->getAlignCenter($part, $feed, $width, $font, $fontSize);
                                }
                                break;
                        }
                        $page->drawText($part, $feed, $this->y-$top, 'UTF-8');
                        $top += $lineSpacing;
                    }

                    $maxHeight = $top > $maxHeight ? $top : $maxHeight;
                }
                $this->y -= $maxHeight;
            }
        }

        return $page;
    }

    protected function _setFontItalic($object, $size = 10)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }
    
    public function newPage(array $settings = array())
    {
        $pdf = $this->_getPdf();

        $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
        $pdf->pages[] = $page;

        if ($this->impressum && Mage::getStoreConfig('sales_pdf/invoice/showfooter') == 1) {
            $this->y = 100;
            $this->insertFooter($page);
        }

        $this->pagecounter++;
        $this->y = 110;
        $this->insertPageCounter($page);
        
        $this->y = 800;
        $this->_setFontRegular($page, 9);

        return $page;
    }
    
}