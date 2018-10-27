<?php

set_time_limit(0);
define('MAGENTO', "/home/discount/public_html");
require_once './app/Mage.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
umask(0);
Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
$storeId = Mage::app()->getStore('default')->getId();

//to overwrite limit but you need first to increase your memory limit

 $collection = Mage::getModel('catalog/product')->getCollection()
->addAttributeToSelect('*') // select all attributes
->setPageSize(5000) // limit number of results returned
->setCurPage(1); // set the offset (useful for pagination)
/*
// we iterate through the list of products to get attribute values
foreach ($collection as $product) {
  echo $product->getId()."Id with name ".$product->getName(); //get name
  echo '<br />Title:';
  echo $product->getMetaTitle();
  echo $newmetatitle= strtolower(remove_special_chrs($product->getName())); //get name
   $newmetatitle="Buy ".$newmetatitle." in Westland, MI from theembroideryshoppe.com ";
   $newmetadescription= $newmetatitle; //get name
	 
	 $simpleProduct = Mage::getModel('catalog/product')->load($product->getId());
	if($simpleProduct) {
		$simpleProduct->setMetaTitle($newmetatitle);
		$simpleProduct->setMetaDescription($newmetadescription);
		
		$simpleProduct->save();
		echo "Updated product " . $product->getId() . "<br>";
	}

	 
	
  echo '<br />';
}
*/

function remove_special_chrs ($string="") {
 
 // PREG_REPLACE REMOVE ALL OTHER CHARACTERS THAT NOT AVAIALABLE IN PREG_REPLACE FIRST
 // PARAMETER YOU CANNOT UNDERSTAND FIRST PARAMETER YOU MUST READ PHP REGULAR EXPRESSION!
 $string = preg_replace('/[^A-Za-z0-9.%-\/]/',' ',$string);
 
 //STRIP_TAGS REMOVE HTML TAGS
 $string=strip_tags($string,"");
 
 //HERE WE REMOVE WHITE SPACES AND RETURN IT
 return trim($string);
}


?>