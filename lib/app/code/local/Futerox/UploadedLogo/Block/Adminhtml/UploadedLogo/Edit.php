<?php

class Futerox_UploadedLogo_Block_Adminhtml_UploadedLogo_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'uploadedlogo';
        $this->_controller = 'adminhtml_uploadedlogo';
        
        $this->_updateButton('save', 'label', Mage::helper('uploadedlogo')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('uploadedlogo')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('uploadedlogo_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'uploadedlogo_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'uploadedlogo_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('uploadedlogo_data') && Mage::registry('uploadedlogo_data')->getId() ) {
            return Mage::helper('uploadedlogo')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('uploadedlogo_data')->getTitle()));
        } else {
            return Mage::helper('uploadedlogo')->__('Add Item');
        }
    }
}