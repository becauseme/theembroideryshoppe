<?php

class Videogallery_Videogallery_Model_Mysql4_Videogallery_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('videogallery/videogallery');
    }
}