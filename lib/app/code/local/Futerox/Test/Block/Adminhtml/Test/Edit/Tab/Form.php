<?php

class Futerox_Test_Block_Adminhtml_Test_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('test_form', array('legend'=>Mage::helper('test')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('test')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('test')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('test')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('test')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('test')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('test')->__('Content'),
          'title'     => Mage::helper('test')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getTestData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getTestData());
          Mage::getSingleton('adminhtml/session')->setTestData(null);
      } elseif ( Mage::registry('test_data') ) {
          $form->setValues(Mage::registry('test_data')->getData());
      }
      return parent::_prepareForm();
  }
}