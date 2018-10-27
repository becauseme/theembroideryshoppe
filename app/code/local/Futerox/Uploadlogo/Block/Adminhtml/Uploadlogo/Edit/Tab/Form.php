<?php

class Futerox_Uploadlogo_Block_Adminhtml_Uploadlogo_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('uploadlogo_form', array('legend'=>Mage::helper('uploadlogo')->__('Item information')));
     
     

      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('Logo Image File Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  
	  $fieldset->addField('contactname', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('Contact Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'contactname',
      ));
	  
	   $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('uploadlogo')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
	  
	  $fieldset->addField('companyname', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('Company Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'companyname',
      ));
	 $fieldset->addField('address', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('Address'),
          'required'  => false,
          'name'      => 'address',
      ));
     $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('City'),
          'required'  => false,
          'name'      => 'city',
      ));
	  
	  $fieldset->addField('state', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('Sate'),
         'required'  => false,
          'name'      => 'state',
      ));
	  
	   $fieldset->addField('zipcode', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('Zip Code'),
          'required'  => false,
          'name'      => 'zipcode',
      ));
	  
	   $fieldset->addField('phone', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('Phone'),
          'required'  => false,
          'name'      => 'phone',
      ));
	  
	  $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('uploadlogo')->__('E-Mail'),
          'required'  => false,
          'name'      => 'email',
      ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('uploadlogo')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('uploadlogo')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('uploadlogo')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('uploadlogo')->__('Comments'),
          'title'     => Mage::helper('uploadlogo')->__('Comments'),
          'style'     => 'width:300px; height:200px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getUploadlogoData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getUploadlogoData());
          Mage::getSingleton('adminhtml/session')->setUploadlogoData(null);
      } elseif ( Mage::registry('uploadlogo_data') ) {
          $form->setValues(Mage::registry('uploadlogo_data')->getData());
      }
      return parent::_prepareForm();
  }
}