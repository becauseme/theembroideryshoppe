<?php
/**
 * Symmetrics_InvoicePdf_Model_Pdf_Invoice
 *
 * @category Symmetrics
 * @package Symmetrics_InvoicePdf
 * @author symmetrics gmbh <info@symmetrics.de>, Eugen Gitin <eg@symmetrics.de>
 * @copyright symmetrics gmbh
 * @license http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Symmetrics_InvoicePdf_Model_Pdf_Invoice extends Mage_Sales_Model_Order_Pdf_Abstract
{
	public $colors;
	public $encoding;
	public $margin;
	public $impressum;
	public $pagecounter;
	public $mode;
	
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
		
		$this->setMode('invoice');
	}
	
    public function getPdf($invoices = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');
        
        $mode = $this->getMode();

        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
        
        $this->pagecounter = 1;

        foreach ($invoices as $invoice) {
        	$page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
            $pdf->pages[] = $page;

            $order = $invoice->getOrder();

			/* add logo */
			$this->insertLogo($page, $invoice->getStore());
			
			/* add billing address */
			$this->y = 692;
            $this->insertBillingAddress($page, $order);

            /* add sender address */
            $this->y = 705;
            $this->insertSenderAddress($page);
            
            /* add header */
			$this->y = 592;
			$this->insertHeader($page, $order, $invoice);

            /* 
             * add footer if the impressum module is 
             * installed and "insert footer" switch 
             * in configuration is enabled 
             * */
			if ($this->impressum && Mage::getStoreConfig('sales_pdf/invoice/showfooter') == 1) {
                $this->y = 110;
                $this->insertFooter($page, $invoice);
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
            foreach ($invoice->getAllItems() as $item) {
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                
                if ($this->y < 200) {
                    $page = $this->newPage(array());
                }
                
                $position++;
                $page = $this->_drawItem($item, $page, $order, $position);
            }

            /* add totals */
            $page = $this->insertTotals($page, $invoice);

            /* add note */
            if ($mode == 'invoice') {            
            	$this->insertNote($page, $order);
            }
        }

        $this->_afterGetPdf();

        return $pdf;
    }

    protected function insertNote($page, $order)
    {
		$this->_setFontRegular($page, 10);

		/*
    	$maturity = Mage::helper('invoicepdf')->__('Invoice maturity: %s days', Mage::getStoreConfig('sales_pdf/invoice/maturity'));

		if (!empty($maturity)) {
			$page->drawText($maturity, $this->margin['left'], $this->y + 50, $this->encoding);
		}
		
		$this->Ln(15);
		*/
		
		//$notice = Mage::helper('invoicepdf')->__('Invoice date is equal to delivery date');
		//$page->drawText($notice, $this->margin['left'], $this->y + 50, $this->encoding);
		
		$note = Mage::getStoreConfig('sales_pdf/invoice/note');

		if (!empty($note)) {
			$page->drawText($note, $this->margin['left'], $this->y + 30, $this->encoding);
		}
		
		// inserted by Simon Sprankel  on 26th Octobre 2009
		// draw our individual texts
		$this->Ln(30);
		
		$ourNote1 = 'Leistungsdatum entspricht Rechnungsdatum.';
		$ourNote2 = 'Es gelten unsere allgemeinen Verkaufs-, Lieferungs- und Zahlungsbedingungen.';
		$ourNote3 = 'Sämtliche Ware bleibt bis zur vollständigen Bezahlung unser Eigentum.';
		
		$page->drawText($ourNote1, $this->margin['left'], $this->y + 30, $this->encoding);
		$this->Ln(15);
		$page->drawText($ourNote2, $this->margin['left'], $this->y + 30, $this->encoding);
		$this->Ln(15);
		$page->drawText($ourNote3, $this->margin['left'], $this->y + 30, $this->encoding);
		$this->Ln(15);
		$page->drawText('Lieferadresse: '.$order->getShippingAddress()->format('pdf'), $this->margin['left'], $this->y + 30, $this->encoding);
		
    }
    
    protected function insertPageCounter(&$page)
    {
    	$font = $this->_setFontRegular($page, 9);
    	$page->drawText(Mage::helper('invoicepdf')->__('Page').' '.$this->pagecounter, $this->margin['right'] - 23 - $this->widthForStringUsingFontSize($this->pagecounter, $font, 9), $this->y, $this->encoding);
    }
    
    protected function insertFooter(&$page, $invoice = null) 
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
    
    protected function insertTableHeader(&$page)
    {
		$page->setFillColor($this->colors['grey1']);
		$page->setLineColor($this->colors['grey1']);
		$page->setLineWidth(1);
		$page->drawRectangle($this->margin['left'], $this->y, $this->margin['right'], $this->y - 15);

		$page->setFillColor($this->colors['black']);
		$font = $this->_setFontRegular($page, 9);
		
		$this->y -= 11;
		$page->drawText(Mage::helper('invoicepdf')->__('Pos'), 			$this->margin['left'] + 3, 		$this->y, $this->encoding);
		$page->drawText(Mage::helper('invoicepdf')->__('No.'), 			$this->margin['left'] + 45, 	$this->y, $this->encoding);
		$page->drawText(Mage::helper('invoicepdf')->__('Description'), 	$this->margin['left'] + 110, 	$this->y, $this->encoding);
		
		$singlePrice = Mage::helper('invoicepdf')->__('Price');
		$page->drawText($singlePrice, $this->margin['right'] - 160 - $this->widthForStringUsingFontSize($singlePrice, $font, 9), 	$this->y, $this->encoding);
		
		$page->drawText(Mage::helper('invoicepdf')->__('Amount'), 		$this->margin['left'] + 360, 	$this->y, $this->encoding);
		
		$taxLabel = Mage::helper('invoicepdf')->__('Tax');
		$page->drawText($taxLabel, $this->margin['right'] - 65 - $this->widthForStringUsingFontSize($taxLabel, $font, 9), $this->y, $this->encoding);
		
		$totalLabel = Mage::helper('invoicepdf')->__('Total');
		$page->drawText($totalLabel, $this->margin['right'] - 10 - $this->widthForStringUsingFontSize($totalLabel, $font, 10), 	$this->y, $this->encoding);
    }
    
    protected function insertHeader(&$page, $order, $invoice)
    {
    	$page->setFillColor($this->colors['black']);
    	
    	$mode = $this->getMode();
    	
    	$this->_setFontBold($page, 15);

    	$page->drawText(Mage::helper('invoicepdf')->__( ($mode == 'invoice') ? 'Invoice' : 'Creditmemo' ), $this->margin['left'], $this->y, $this->encoding);

    	$this->_setFontRegular($page);    	
    	
    	$this->y += 60;
    	$rightoffset = 200;
    	
    	$page->drawText(Mage::helper('invoicepdf')->__( ($mode == 'invoice') ? 'Invoice number:' : 'Creditmemo number:' ), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
		$this->Ln();
		$page->drawText('Order Number.:', $this->margin['right'] - $rightoffset, $this->y, $this->encoding);
    	$this->Ln();
    	$page->drawText(Mage::helper('invoicepdf')->__('Customer number:'), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	$this->Ln();
    	
    	$yPlus = 30;
    	
    	if(Mage::getStoreConfig('sales_pdf/invoice/showcustomerip')) {
        	$page->drawText(Mage::helper('invoicepdf')->__('Customer IP:'), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
        	$this->Ln();
        	$yPlus = 45;
    	}

    	$page->drawText(Mage::helper('invoicepdf')->__('Invoice date:'), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	$this->Ln();
    	$page->drawText('Payment method:', ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	
    	//$this->y += $yPlus;
		$this->y += 60;
    	$rightoffset = 80;
		// take invoice id, not order id!
    	//$page->drawText($order->getRealOrderId(), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	$page->drawText($invoice->getIncrementId(), ($this->margin['right'] - $rightoffset), $this->y, $this->encoding);
    	$this->Ln();
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
    
    protected function insertBillingAddress(&$page, $order)
    {
		$this->_setFontRegular($page, 9);
		
		$billing = $this->_formatAddress($order->getBillingAddress()->format('pdf'));
		
		foreach ($billing as $line) {
			$page->drawText(trim(strip_tags($line)), $this->margin['left'], $this->y, $this->encoding);
			$this->Ln(12);
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
    
    protected function insertLogo(&$page, $store = null) 
    {
    	$maxwidth = 145;
    	$maxheight = 95;
    	
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

                $position['x1'] = $startLogoAt + 25;
                $position['y1'] = 682;
                $position['x2'] = $position['x1'] + $width;
                $position['y2'] = $position['y1'] + $height;
               
                $page->drawImage($image, $position['x1'], $position['y1'], $position['x2'], $position['y2']);
            }
        }
    }
    
    protected function insertTotals($page, $source)
    {
    	$this->y -=15;
    	
    	$order = $source->getOrder();

        $tax = Mage::getModel('sales/order_tax')->getCollection()->loadByOrder($order)->toArray();

        $total_tax = 0;
        $shippingTaxAmount = $order->getShippingTaxAmount();
        $groupedTax = array();

        foreach ($source->getAllItems() as $item) {
            if ($item->getOrderItem()->getParentItem()) {
                continue;
            }
            $items['items'][] = $item->getOrderItem()->toArray();
        }

        $add_totals = unserialize(Mage::getModel('sales/quote')->getCollection()
            ->getItemById($order->getQuoteId())
            ->getInvoicepdfAddTotals());

        if ($add_totals) {
            foreach ( $add_totals as $add_total ) {
                array_push($items['items'], array(
                    'tax_inc_subtotal' => false,
                    'tax_percent' => number_format($add_total['tax']['percent'], 4, '.', ''),
                    'tax_amount' => $add_total['tax']['amount']
                ));
            }
        }

        array_push($items['items'], array(
            'tax_inc_subtotal' => false,
            'tax_percent' => '19.0000',
            'tax_amount' => $shippingTaxAmount
        ));
        
        foreach ($items['items'] as $item) {
            if (!array_key_exists('tax_inc_subtotal', $item) || $item['tax_inc_subtotal']) {
                $total_tax += $item['tax_amount'];
            }

            if ($item['tax_amount']) {
                if(!array_key_exists($item['tax_percent'], $groupedTax)) {
                    $groupedTax[$item['tax_percent']] = $item['tax_amount'];
                }
                else {
                    $groupedTax[$item['tax_percent']] += $item['tax_amount'];
                }
            }
        }

        $totals = $this->_getTotalsList($source);

        $lineBlock = array(
            'lines'  => array(),
            'height' => 20
        );

        foreach ($totals as $total) {
            $fontSize = (isset($total['font_size']) ? $total['font_size'] : 7);
            if ($fontSize < 9) {
                $fontSize = 9;
            }
            $fontWeight = (isset($total['font_weight']) ? $total['font_weight'] : 'regular');

        	switch($total['source_field']) {
        		case 'tax_amount':
        	        foreach ($groupedTax as $taxRate => $taxValue) {
                        if(empty($taxValue)) {
                            continue;
                        }
                        
                        $lineBlock['lines'][] = array(
                            array(
                                'text'      => Mage::helper('invoicepdf')->__('Additional tax %s', $source->getStore()->roundPrice(number_format($taxRate, 0)).'%'),
                                'feed'      => $this->margin['right'] - 100,
                                'align'     => 'right',
                                'font_size' => $fontSize,
                                'font'      => $fontWeight
                            ),
                            array(
                                'text'      => $order->formatPriceTxt($taxValue),
                                'feed'      => $this->margin['right'] - 10,
                                'align'     => 'right',
                                'font_size' => $fontSize,
                                'font'      => $fontWeight
                            ),
                        );
                    }
                break;
        		default:
                    $amount = $source->getDataUsingMethod($total['source_field']);
                    $displayZero = (isset($total['display_zero']) ? $total['display_zero'] : 0);

                    if ($amount != 0 || $displayZero) {
                        $amount = $order->formatPriceTxt($amount);

                        if (isset($total['amount_prefix']) && $total['amount_prefix']) {
                            $amount = "{$total['amount_prefix']}{$amount}";
                        }

                        $label = Mage::helper('sales')->__($total['title']) . ':';

                        $lineBlock['lines'][] = array(
                            array(
                                'text'      => $label,
                                'feed'      => $this->margin['right'] - 100,
                                'align'     => 'right',
                                'font_size' => $fontSize,
                                'font'      => $fontWeight
                            ),
                            array(
                                'text'      => $amount,
                                'feed'      => $this->margin['right'] - 10,
                                'align'     => 'right',
                                'font_size' => $fontSize,
                                'font'      => $fontWeight
                            ),
                        );
                    }
        	}
        }
        
        $page = $this->drawLineBlocks($page, array($lineBlock));
        return $page;
    }
    
    protected function Ln($height=15)
	{
    	$this->y -= $height;
    }
    
    protected function _setFontRegular($object, $size = 10)
    {
    	$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
        $object->setFont($font, $size);
        return $font;
    }

    protected function _setFontBold($object, $size = 10)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD);
        $object->setFont($font, $size);
        return $font;
    }

    protected function _setFontItalic($object, $size = 10)
    {
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_ITALIC);
        $object->setFont($font, $size);
        return $font;
    }
    
    protected function _drawItem(Varien_Object $item, Zend_Pdf_Page $page, Mage_Sales_Model_Order $order, $position = 1)
    {
        $type = $item->getOrderItem()->getProductType();
        $renderer = $this->_getRenderer($type);
        $renderer->setOrder($order);
        $renderer->setItem($item);
        $renderer->setPdf($this);
        $renderer->setPage($page);
        $renderer->setRenderedModel($this);

        $renderer->draw($position);

        return $renderer->getPage();
    }
    
    public function setMode($mode = 'invoice')
    {
    	$this->mode = $mode;
    }

    public function getMode()
    {
    	return $this->mode;
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
    
    protected function insertSenderAddress($page) 
    {
        if($senderAddress = Mage::getStoreConfig('sales_pdf/invoice/senderaddress')) {
            $this->_setFontRegular($page, 7);
            $page->drawText($senderAddress,  $this->margin['left'], $this->y, $this->encoding);
        }
        return;
    }
}
