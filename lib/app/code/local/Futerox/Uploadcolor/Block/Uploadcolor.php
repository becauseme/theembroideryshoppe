<?php
class Futerox_Uploadcolor_Block_Uploadcolor extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getUploadcolor()     
     { 
        if (!$this->hasData('uploadcolor')) {
            $this->setData('uploadcolor', Mage::registry('uploadcolor'));
        }
        return $this->getData('uploadcolor');
        
    }
}