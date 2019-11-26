<?php

class Futerox_Bannerhome_Model_Mysql4_Bannerhome_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bannerhome/bannerhome');
    }
}