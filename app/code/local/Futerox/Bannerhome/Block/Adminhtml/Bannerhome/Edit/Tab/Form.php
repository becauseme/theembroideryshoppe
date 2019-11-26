<?php

class Futerox_Bannerhome_Block_Adminhtml_Bannerhome_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('bannerhome_form', array('legend'=>Mage::helper('bannerhome')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('bannerhome')->__('bannerhome Link'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('bannerhome')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
     
     
     
      if ( Mage::getSingleton('adminhtml/session')->getBannerhomeData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getBannerhomeData());
          Mage::getSingleton('adminhtml/session')->setBannerhomeData(null);
      } elseif ( Mage::registry('bannerhome_data') ) {
          $form->setValues(Mage::registry('bannerhome_data')->getData());
      }
      return parent::_prepareForm();
  }
}