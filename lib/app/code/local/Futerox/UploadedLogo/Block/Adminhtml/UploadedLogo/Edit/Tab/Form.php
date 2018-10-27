<?php

class Futerox_UploadedLogo_Block_Adminhtml_UploadedLogo_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('uploadedlogo_form', array('legend'=>Mage::helper('uploadedlogo')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('Logo Image File Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));
	  
	  $fieldset->addField('contactname', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('Contact Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'contactname',
      ));
	  
	   $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('uploadedlogo')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
	  
	  $fieldset->addField('companyname', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('Company Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'companyname',
      ));
	 $fieldset->addField('address', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('Address'),
          'required'  => false,
          'name'      => 'address',
      ));
     $fieldset->addField('city', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('City'),
          'required'  => false,
          'name'      => 'city',
      ));
	  
	  $fieldset->addField('state', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('Sate'),
         'required'  => false,
          'name'      => 'state',
      ));
	  
	   $fieldset->addField('zipcode', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('Zip Code'),
          'required'  => false,
          'name'      => 'zipcode',
      ));
	  
	   $fieldset->addField('phone', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('Phone'),
          'required'  => false,
          'name'      => 'phone',
      ));
	  
	  $fieldset->addField('email', 'text', array(
          'label'     => Mage::helper('uploadedlogo')->__('E-Mail'),
          'required'  => false,
          'name'      => 'email',
      ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('uploadedlogo')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('uploadedlogo')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('uploadedlogo')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('uploadedlogo')->__('Comments'),
          'title'     => Mage::helper('uploadedlogo')->__('Comments'),
          'style'     => 'width:300px; height:200px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getUploadedLogoData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getUploadedLogoData());
          Mage::getSingleton('adminhtml/session')->setUploadedLogoData(null);
      } elseif ( Mage::registry('uploadedlogo_data') ) {
          $form->setValues(Mage::registry('uploadedlogo_data')->getData());
      }
      return parent::_prepareForm();
  }
}