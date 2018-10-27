<?php

class Futerox_Uploadcolor_Model_Mysql4_Uploadcolor_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('uploadcolor/uploadcolor');
    }
}