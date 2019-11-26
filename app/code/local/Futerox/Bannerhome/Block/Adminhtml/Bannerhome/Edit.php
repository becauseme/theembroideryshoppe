<?php

class Futerox_Bannerhome_Block_Adminhtml_Bannerhome_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'bannerhome';
        $this->_controller = 'adminhtml_bannerhome';
        
        $this->_updateButton('save', 'label', Mage::helper('bannerhome')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('bannerhome')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('bannerhome_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'bannerhome_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'bannerhome_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('bannerhome_data') && Mage::registry('bannerhome_data')->getId() ) {
            return Mage::helper('bannerhome')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('bannerhome_data')->getTitle()));
        } else {
            return Mage::helper('bannerhome')->__('Add Item');
        }
    }
}