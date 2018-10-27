<?php

class Futerox_Uploadcolor_Model_Mysql4_Uploadcolor extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the uploadcolor_id refers to the key field in your database table.
        $this->_init('uploadcolor/uploadcolor', 'uploadcolor_id');
    }
}