<?php


/**
 * Sales Order Invoice Pdf grouped items renderer
 *
 * @category   Spranks
 * @package    Spranks_Packingslip
 */
class Spranks_Packingslip_Model_Order_Pdf_Items_Invoice_Grouped extends Spranks_Packingslip_Model_Order_Pdf_Items_Invoice_Default
{
    public function draw($position = 1)
    {
        $type = $this->getItem()->getOrderItem()->getRealProductType();
        $renderer = $this->getRenderedModel()->getRenderer($type);
        $renderer->setOrder($this->getOrder());
        $renderer->setItem($this->getItem());
        $renderer->setPdf($this->getPdf());
        $renderer->setPage($this->getPage());

        $renderer->draw($position);
    }
}