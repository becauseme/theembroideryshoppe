<?php
class Futerox_Bannerhome_Block_Bannerhome extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getBanner()     
     { 
        if (!$this->hasData('bannerhome')) {
            $this->setData('bannerhome', Mage::registry('bannerhome'));
        }
        return $this->getData('bannerhome');
        
    }
}