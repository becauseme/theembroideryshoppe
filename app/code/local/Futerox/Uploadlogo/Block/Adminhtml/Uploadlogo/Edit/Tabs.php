<?php

class Futerox_Uploadlogo_Block_Adminhtml_Uploadlogo_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('uploadlogo_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('uploadlogo')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('uploadlogo')->__('Item Information'),
          'title'     => Mage::helper('uploadlogo')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('uploadlogo/adminhtml_uploadlogo_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}