<?php

class Futerox_Uploadlogo_Model_Uploadlogo extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('uploadlogo/uploadlogo');
    }
}