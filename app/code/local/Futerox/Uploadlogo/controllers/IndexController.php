<?php
class Futerox_Uploadlogo_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/uploadlogo?id=15 
    	 *  or
    	 * http://site.com/uploadlogo/id/15 	
    	 */
    	/* 
		$uploadlogo_id = $this->getRequest()->getParam('id');

  		if($uploadlogo_id != null && $uploadlogo_id != '')	{
			$uploadlogo = Mage::getModel('uploadlogo/uploadlogo')->load($uploadlogo_id)->getData();
		} else {
			$uploadlogo = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($uploadlogo == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$uploadlogoTable = $resource->getTableName('uploadlogo');
			
			$select = $read->select()
			   ->from($uploadlogoTable,array('uploadlogo_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$uploadlogo = $read->fetchRow($select);
		}
		Mage::register('uploadlogo', $uploadlogo);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}