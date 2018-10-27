<?php

class Futerox_Test_Block_Adminhtml_Test_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'test';
        $this->_controller = 'adminhtml_test';
        
        $this->_updateButton('save', 'label', Mage::helper('test')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('test')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('test_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'test_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'test_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('test_data') && Mage::registry('test_data')->getId() ) {
            return Mage::helper('test')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('test_data')->getTitle()));
        } else {
            return Mage::helper('test')->__('Add Item');
        }
    }
}