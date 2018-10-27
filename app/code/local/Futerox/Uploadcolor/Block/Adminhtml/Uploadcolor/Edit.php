<?php

class Futerox_Uploadcolor_Block_Adminhtml_Uploadcolor_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'uploadcolor';
        $this->_controller = 'adminhtml_uploadcolor';
        
        $this->_updateButton('save', 'label', Mage::helper('uploadcolor')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('uploadcolor')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('uploadcolor_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'uploadcolor_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'uploadcolor_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('uploadcolor_data') && Mage::registry('uploadcolor_data')->getId() ) {
            return Mage::helper('uploadcolor')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('uploadcolor_data')->getTitle()));
        } else {
            return Mage::helper('uploadcolor')->__('Add Item');
        }
    }
}