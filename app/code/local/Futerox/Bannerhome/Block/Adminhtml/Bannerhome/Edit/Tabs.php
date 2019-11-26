<?php

class Futerox_Bannerhome_Block_Adminhtml_Bannerhome_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('bannerhome_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('bannerhome')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('bannerhome')->__('Item Information'),
          'title'     => Mage::helper('bannerhome')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('bannerhome/adminhtml_bannerhome_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}