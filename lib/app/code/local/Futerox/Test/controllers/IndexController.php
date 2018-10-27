<?php
class Futerox_Test_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/test?id=15 
    	 *  or
    	 * http://site.com/test/id/15 	
    	 */
    	/* 
		$test_id = $this->getRequest()->getParam('id');

  		if($test_id != null && $test_id != '')	{
			$test = Mage::getModel('test/test')->load($test_id)->getData();
		} else {
			$test = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($test == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$testTable = $resource->getTableName('test');
			
			$select = $read->select()
			   ->from($testTable,array('test_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$test = $read->fetchRow($select);
		}
		Mage::register('test', $test);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}