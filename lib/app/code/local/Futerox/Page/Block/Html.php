<?php
class Futerox_Mage_Page_Block_Html extends Mage_Page_Block_Html
{
public function getFeaturedProductHtml()
{
return $this->getBlockHtml('product_featured');
}
public function getUploadlogoProductHtml()
{
return $this->getBlockHtml('product_uploadlogo');
}

}
?>