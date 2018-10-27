<?php

class Futerox_Test_Model_Mysql4_Test extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the test_id refers to the key field in your database table.
        $this->_init('test/test', 'test_id');
    }
}