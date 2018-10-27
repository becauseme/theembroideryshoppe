<?php

class Futerox_UploadedLogo_Model_Mysql4_UploadedLogo extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the uploadedlogo_id refers to the key field in your database table.
        $this->_init('uploadedlogo/uploadedlogo', 'uploadedlogo_id');
    }
}