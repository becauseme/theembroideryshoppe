<?php
class Videogallery_Videogallery_Block_Adminhtml_Videogallery extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_videogallery';
    $this->_blockGroup = 'videogallery';
    $this->_headerText = Mage::helper('videogallery')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('videogallery')->__('Add Item');
    parent::__construct();
  }
}