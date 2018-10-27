<?php
class Futerox_Uploadlogo_Block_Adminhtml_Uploadlogo extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_uploadlogo';
    $this->_blockGroup = 'uploadlogo';
    $this->_headerText = Mage::helper('uploadlogo')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('uploadlogo')->__('Add Item');
    parent::__construct();
  }
}