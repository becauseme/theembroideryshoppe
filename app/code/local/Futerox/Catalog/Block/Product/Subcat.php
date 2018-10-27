<?php
class Futerox_Catalog_Block_Product_Subcat extends Mage_Catalog_Block_Product_Abstract
{
public function getSubcatProduct()
    {
        $layer = Mage::getSingleton('catalog/layer');
        $category   = $layer->getCurrentCategory();
        $collection = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect('url_key')
/* begin */
            ->addAttributeToSelect('image')
/*end */	->addAttributeToSelect('name')
            ->addAttributeToSelect('all_children')
			//->addAttributeToSelect('model')
            ->addAttributeToSelect('is_anchor')
			->addAttributeToSelect('*')
            ->addIdFilter($category->getChildren())
            ->load();

        $productCollection = Mage::getResourceModel('catalog/product_collection');
        $layer->prepareProductCollection($productCollection);
        $productCollection->addCountToCategories($collection);
		return $collection;
        /*$parent = $this->getRequest()->getParam('id');
        return $this->_getChildCategories($parent, 1);*/
		//echo $this->_getChildCategories($parent, 1);
		//exit;
    } 
}
?>