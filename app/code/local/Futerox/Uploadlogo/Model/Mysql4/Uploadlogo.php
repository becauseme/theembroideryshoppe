<?php

class Futerox_Uploadlogo_Model_Mysql4_Uploadlogo extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the uploadlogo_id refers to the key field in your database table.
        $this->_init('uploadlogo/uploadlogo', 'uploadlogo_id');
    }
}