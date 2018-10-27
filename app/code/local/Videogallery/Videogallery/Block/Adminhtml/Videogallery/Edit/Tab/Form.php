<?php

class Videogallery_Videogallery_Block_Adminhtml_Videogallery_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('videogallery_form', array('legend'=>Mage::helper('videogallery')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('videogallery')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

     /* $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('videogallery')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  )); */
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('videogallery')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('videogallery')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('videogallery')->__('Disabled'),
              ),
          ),
      ));
     
	  $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('videogallery')->__('Embed Code'),
          'title'     => Mage::helper('videogallery')->__('Embed Code'),
          'style'     => 'width:700px; height:150px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
	 
	 
	  $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('videogallery')->__('Description'),
          'title'     => Mage::helper('videogallery')->__('Description'),
          'style'     => 'width:700px; height:150px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
	 
	 
     
	  
	 
     
      if ( Mage::getSingleton('adminhtml/session')->getVideogalleryData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getVideogalleryData());
          Mage::getSingleton('adminhtml/session')->setVideogalleryData(null);
      } elseif ( Mage::registry('videogallery_data') ) {
          $form->setValues(Mage::registry('videogallery_data')->getData());
      }
      return parent::_prepareForm();
  }
}