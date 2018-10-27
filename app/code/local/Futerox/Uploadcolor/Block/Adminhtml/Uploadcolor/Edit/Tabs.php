<?php

class Futerox_Uploadcolor_Block_Adminhtml_Uploadcolor_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('uploadcolor_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('uploadcolor')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('uploadcolor')->__('Item Information'),
          'title'     => Mage::helper('uploadcolor')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('uploadcolor/adminhtml_uploadcolor_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}