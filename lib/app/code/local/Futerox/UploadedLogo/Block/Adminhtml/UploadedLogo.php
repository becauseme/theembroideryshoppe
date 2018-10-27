<?php
class Futerox_UploadedLogo_Block_Adminhtml_UploadedLogo extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_uploadedlogo';
    $this->_blockGroup = 'uploadedlogo';
    $this->_headerText = Mage::helper('uploadedlogo')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('uploadedlogo')->__('Add Item');
    parent::__construct();
  }
}