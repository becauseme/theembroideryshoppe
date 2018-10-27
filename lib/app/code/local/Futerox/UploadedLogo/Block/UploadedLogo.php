<?php
class Futerox_UploadedLogo_Block_UploadedLogo extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getUploadedLogo()     
     { 
        if (!$this->hasData('uploadedlogo')) {
            $this->setData('uploadedlogo', Mage::registry('uploadedlogo'));
        }
        return $this->getData('uploadedlogo');
        
    }
}