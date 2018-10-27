<?php
class Futerox_UploadedLogo_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/uploadedlogo?id=15 
    	 *  or
    	 * http://site.com/uploadedlogo/id/15 	
    	 */
    	/* 
		$uploadedlogo_id = $this->getRequest()->getParam('id');

  		if($uploadedlogo_id != null && $uploadedlogo_id != '')	{
			$uploadedlogo = Mage::getModel('uploadedlogo/uploadedlogo')->load($uploadedlogo_id)->getData();
		} else {
			$uploadedlogo = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($uploadedlogo == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$uploadedlogoTable = $resource->getTableName('uploadedlogo');
			
			$select = $read->select()
			   ->from($uploadedlogoTable,array('uploadedlogo_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$uploadedlogo = $read->fetchRow($select);
		}
		Mage::register('uploadedlogo', $uploadedlogo);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}