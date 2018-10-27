<?php

/**
 * Adminhtml sales orders controller with order printing action
 *
 * @category    Spranks
 * @package     Spranks_Packingslip
 * @author      Piotr Nastały <piotr.nastaly@nastnet.com> ; Simon Sprankel <ssprankel@badminton-outlet.de>
 */
require_once 'Mage/Adminhtml/controllers/Sales/OrderController.php';
class Spranks_Packingslip_OrderController extends Mage_Adminhtml_Sales_OrderController
{

    
    /**
     * Print order
     * 
     */
	public function printAction(){
        $order = $this->_initOrder();
        if (!empty($order)) {
			$order->setOrder($order);
            $pdf = Mage::getModel('Spranks_Packingslip/order_pdf_order')->getPdf(array($order));
            return $this->_prepareDownloadResponse('packingslip'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(), 'application/pdf');
        }
        $this->_redirect('*/*/');
    }
	
	public function pdfordersAction(){
        $orderIds = $this->getRequest()->getPost('order_ids');
        $flag = false;
        if (!empty($orderIds)) {
            foreach ($orderIds as $orderId) {
                $order = Mage::getModel('sales/order')->load($orderId);
                $flag = true;
                $order->setOrder($order);
                if (!isset($pdf)){
                $pdf = Mage::getModel('Spranks_Packingslip/order_pdf_order')->getPdf(array($order));
                } else {
                $pages = Mage::getModel('Spranks_Packingslip/order_pdf_order')->getPdf(array($order));
                $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                }
            }
            if ($flag) {
                return $this->_prepareDownloadResponse('packingslips'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').'.pdf', $pdf->render(), 'application/pdf');
            } else {
                $this->_getSession()->addError($this->__('There are no printable documents related to selected orders'));
                $this->_redirect('*/*/');
            }

        }
        $this->_redirect('*/*/');

    }
    
}