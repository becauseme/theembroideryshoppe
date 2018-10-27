<?php
class Videogallery_Videogallery_Block_Videogallery extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getVideogallery()     
     { 
        if (!$this->hasData('videogallery')) {
            $this->setData('videogallery', Mage::registry('videogallery'));
        }
        return $this->getData('videogallery');
        
    }
}