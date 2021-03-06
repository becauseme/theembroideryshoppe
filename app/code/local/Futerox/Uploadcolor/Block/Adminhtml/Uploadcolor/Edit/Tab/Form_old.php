<?php

class Futerox_Uploadcolor_Block_Adminhtml_Uploadcolor_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('uploadcolor_form', array('legend'=>Mage::helper('uploadcolor')->__('Item information')));
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('uploadcolor')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('uploadcolor')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('uploadcolor')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('uploadcolor')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('uploadcolor')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('uploadcolor')->__('Content'),
          'title'     => Mage::helper('uploadcolor')->__('Content'),
          'style'     => 'width:300px; height:100px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getUploadcolorData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getUploadcolorData());
          Mage::getSingleton('adminhtml/session')->setUploadcolorData(null);
      } elseif ( Mage::registry('uploadcolor_data') ) {
          $form->setValues(Mage::registry('uploadcolor_data')->getData());
      }
      return parent::_prepareForm();
  }
}