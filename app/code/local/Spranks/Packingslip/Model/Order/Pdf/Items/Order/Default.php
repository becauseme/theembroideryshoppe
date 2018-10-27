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
 * @category   Spranke
 * @package    Spranks_Sales
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Sales Order Invoice Pdf default items renderer
 *
 * @category   Spranks
 * @package    Spranks_Packingslip
 * @author     Piotr Nastaly <piotr.nastaly@nastnet.com> ; Simon Sprankel <ssprankel@badminton-outlet.de>
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Spranks_Packingslip_Model_Order_Pdf_Items_Order_Default extends Spranks_Packingslip_Model_Order_Pdf_Items_Abstract
{
    public function draw($position = 1)
    {
		$order  = $this->getOrder();
        $item   = $this->getItem();
        $pdf    = $this->getPdf();
        $page   = $this->getPage();
        $lines  = array();
        
        $fontSize = 9;

        // draw Position Number
		/* we do not want the position number
        $lines[0]= array(array(
            'text'  => $position,
            'feed'  => $pdf->margin['left'] + 15,
            'align' => 'right',
            'font_size' => $fontSize
        ));*/

        // draw QTY
        $lines[0][] = array(
            'text'  => $item->getQtyOrdered() * 1,
            'feed'  => $pdf->margin['left'] + 15,
            'align' => 'right',
            'font_size' => $fontSize
        );
        
        // draw SKU
        $lines[0][] = array(
            'text'  => Mage::helper('core/string')->str_split($this->getSku($item), 15),
            'feed'  => $pdf->margin['left'] + 45,
            'font_size' => $fontSize
        );
        
        // draw Product name
        $lines[0][]= array(
            'text' => Mage::helper('core/string')->str_split($item->getName(), 40, true, true),
            'feed' => $pdf->margin['left'] + 120,
            'font_size' => $fontSize
        );

        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = array(
                    'text' => Mage::helper('core/string')->str_split(strip_tags($option['label']), 40, false, true),
                    'font' => 'bold',
                    'feed' => $pdf->margin['left'] + 160
                );

                // draw options value
                if ($option['value']) {
                    $_printValue = isset($option['print_value']) ? $option['print_value'] : strip_tags($option['value']);
                    $values = explode(', ', $_printValue);
                    foreach ($values as $value) {
                        $lines[][] = array(
                            'text' => Mage::helper('core/string')->str_split($value, 60, true, true),
                            'feed' => $pdf->margin['left'] + 170
                        );
                    }
                }
            }
        }

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 15
        );

        $page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $this->setPage($page);
    }
    
}