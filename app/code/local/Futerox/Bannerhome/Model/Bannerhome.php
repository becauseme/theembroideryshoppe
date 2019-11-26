<?php

class Futerox_Bannerhome_Model_Bannerhome extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('bannerhome/bannerhome');
    }
}