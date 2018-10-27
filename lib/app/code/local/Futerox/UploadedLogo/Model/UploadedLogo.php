<?php

class Futerox_UploadedLogo_Model_UploadedLogo extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('uploadedlogo/uploadedlogo');
    }
}