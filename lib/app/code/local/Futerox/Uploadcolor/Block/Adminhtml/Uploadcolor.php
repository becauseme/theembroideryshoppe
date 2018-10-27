<?php
class Futerox_Uploadcolor_Block_Adminhtml_Uploadcolor extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_uploadcolor';
    $this->_blockGroup = 'uploadcolor';
    $this->_headerText = Mage::helper('uploadcolor')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('uploadcolor')->__('Add Item');
    parent::__construct();
  }
}