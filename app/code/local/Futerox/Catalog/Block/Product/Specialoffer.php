<?php
class Futerox_Catalog_Block_Product_Specialoffer extends Mage_Catalog_Block_Product_Abstract
{
public function getSpecialProducts() {
$resource = Mage::getSingleton('core/resource');
$read = $resource->getConnection('catalog_read');
$productEntityIntTable = (string)Mage::getConfig()->getTablePrefix() . 'catalog_product_entity_int';
$eavAttributeTable = $resource->getTableName('eav/attribute');
$categoryProductTable = $resource->getTableName('catalog/category_product');
$select = $read->select()
->distinct(true)
->from(array('cp'=>$categoryProductTable), 'product_id')
->join(array('pei'=>$productEntityIntTable), 'pei.entity_id=cp.product_id', array())
->joinNatural(array('ea'=>$eavAttributeTable))
->where('pei.value=1')
->where('ea.attribute_code="special_offer"')
->order('rand()');
//$select=$select." limit $offset, $limit";
$res = $read->fetchAll($select);
return $res;
}
}

?>
