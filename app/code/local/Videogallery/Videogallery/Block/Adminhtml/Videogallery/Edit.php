<?php

class Videogallery_Videogallery_Block_Adminhtml_Videogallery_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'videogallery';
        $this->_controller = 'adminhtml_videogallery';
        
        $this->_updateButton('save', 'label', Mage::helper('videogallery')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('videogallery')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('videogallery_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'videogallery_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'videogallery_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('videogallery_data') && Mage::registry('videogallery_data')->getId() ) {
            return Mage::helper('videogallery')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('videogallery_data')->getTitle()));
        } else {
            return Mage::helper('videogallery')->__('Add Item');
        }
    }
}