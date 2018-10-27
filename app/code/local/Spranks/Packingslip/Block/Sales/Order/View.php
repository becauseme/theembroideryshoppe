<?php
class Spranks_Packingslip_Block_Sales_Order_View extends Mage_Adminhtml_Block_Sales_Order_View {
	
	public function __construct() {
		parent::__construct();
		$this->_addButton('order_print', array(
                //'label'     => Mage::helper('sales')->__('Print'),
				'label'     => 'Print',
                'onclick'   => 'setLocation(\'' . $this->getUrl('*/sales_order/print') . '\')',
            ));
	}
}
?>