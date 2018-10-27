<?php

class Futerox_Uploadlogo_Block_Adminhtml_Uploadlogo_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'uploadlogo';
        $this->_controller = 'adminhtml_uploadlogo';
        
        $this->_updateButton('save', 'label', Mage::helper('uploadlogo')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('uploadlogo')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('uploadlogo_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'uploadlogo_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'uploadlogo_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('uploadlogo_data') && Mage::registry('uploadlogo_data')->getId() ) {
            return Mage::helper('uploadlogo')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('uploadlogo_data')->getTitle()));
        } else {
            return Mage::helper('uploadlogo')->__('Add Item');
        }
    }
}