<?php
class Futerox_Bannerhome_Block_Adminhtml_Bannerhome extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_bannerhome';
    $this->_blockGroup = 'bannerhome';
    $this->_headerText = Mage::helper('bannerhome')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('bannerhome')->__('Add Item');
    parent::__construct();
  }
}