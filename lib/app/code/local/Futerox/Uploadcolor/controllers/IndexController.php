<?php
class Futerox_Uploadcolor_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/uploadcolor?id=15 
    	 *  or
    	 * http://site.com/uploadcolor/id/15 	
    	 */
    	/* 
		$uploadcolor_id = $this->getRequest()->getParam('id');

  		if($uploadcolor_id != null && $uploadcolor_id != '')	{
			$uploadcolor = Mage::getModel('uploadcolor/uploadcolor')->load($uploadcolor_id)->getData();
		} else {
			$uploadcolor = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($uploadcolor == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$uploadcolorTable = $resource->getTableName('uploadcolor');
			
			$select = $read->select()
			   ->from($uploadcolorTable,array('uploadcolor_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$uploadcolor = $read->fetchRow($select);
		}
		Mage::register('uploadcolor', $uploadcolor);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}