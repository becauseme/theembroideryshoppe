<?php

class Videogallery_Videogallery_Model_Videogallery extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('videogallery/videogallery');
    }
}