<?php
class Futerox_Test_Block_Adminhtml_Test extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_test';
    $this->_blockGroup = 'test';
    $this->_headerText = Mage::helper('test')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('test')->__('Add Item');
    parent::__construct();
  }
}