<?php
class Futerox_Uploadlogo_Block_Uploadlogo extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getUploadlogo()     
     { 
        if (!$this->hasData('uploadlogo')) {
            $this->setData('uploadlogo', Mage::registry('uploadlogo'));
        }
        return $this->getData('uploadlogo');
        
    }
}