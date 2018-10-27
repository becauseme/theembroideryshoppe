<?php
class Videogallery_Videogallery_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/videogallery?id=15 
    	 *  or
    	 * http://site.com/videogallery/id/15 	
    	 */
    	/* 
		$videogallery_id = $this->getRequest()->getParam('id');

  		if($videogallery_id != null && $videogallery_id != '')	{
			$videogallery = Mage::getModel('videogallery/videogallery')->load($videogallery_id)->getData();
		} else {
			$videogallery = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($videogallery == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$videogalleryTable = $resource->getTableName('videogallery');
			
			$select = $read->select()
			   ->from($videogalleryTable,array('videogallery_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$videogallery = $read->fetchRow($select);
		}
		Mage::register('videogallery', $videogallery);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}