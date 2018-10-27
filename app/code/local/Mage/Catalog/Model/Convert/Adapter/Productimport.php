<?php
/**
* Product_import.php
* 
* @copyright  copyright (c) 2009 scottbolasevich[at]gmail.com
* @license	http://opensource.org/licenses/osl-3.0.php open software license (OSL 3.0)
*/

class Mage_Catalog_Model_Convert_Adapter_Productimport
extends Mage_Catalog_Model_Convert_Adapter_Product
{
	
	/**
	* Save product (import)
	* 
	* @param array $importData 
	* @throws Mage_Core_Exception
	* @return bool 
	*/
	public function saveRow( array $importData )
	{
		$product = $this -> getProductModel();
		$product -> setData( array() );

		if ( $stockItem = $product -> getStockItem() ) {
			$stockItem -> setData( array() );
		} 
		
		if ( empty( $importData['store'] ) ) {
			if ( !is_null( $this -> getBatchParams( 'store' ) ) ) {
				$store = $this -> getStoreById( $this -> getBatchParams( 'store' ) );
			} else {
				$message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, required field "%s" not defined', 'store' );
				Mage :: throwException( $message );
			} 
		} else {
			$store = $this -> getStoreByCode( $importData['store'] );
		} 
		
		if ( $store === false ) {
			$message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, store "%s" field not exists', $importData['store'] );
			Mage :: throwException( $message );
		} 
		
		if ( empty( $importData['sku'] ) ) {
			$message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, required field "%s" not defined', 'sku' );
			Mage :: throwException( $message );
		} 
		
		$product -> setStoreId( $store -> getId() );
		$productId = $product -> getIdBySku( $importData['sku'] );
		$iscustomoptions = "false"; //sets currentcustomoptionstofalse
		$new = true; // fix for duplicating attributes error
		if ( $productId ) {
			$product -> load( $productId );
			$new = false; // fix for duplicating attributes error
		} 
		$productTypes = $this -> getProductTypes();
		$productAttributeSets = $this -> getProductAttributeSets();
		
		// delete disabled products
		if ( $importData['status'] == 'Disabled' ) {
			$product = Mage :: getSingleton( 'catalog/product' ) -> load( $productId );
			$this -> _removeFile( Mage :: getSingleton( 'catalog/product_media_config' ) -> getMediaPath( $product -> getData( 'image' ) ) );
			$this -> _removeFile( Mage :: getSingleton( 'catalog/product_media_config' ) -> getMediaPath( $product -> getData( 'small_image' ) ) );
			$this -> _removeFile( Mage :: getSingleton( 'catalog/product_media_config' ) -> getMediaPath( $product -> getData( 'thumbnail' ) ) );
			$media_gallery = $product -> getData( 'media_gallery' );
			foreach ( $media_gallery['images'] as $image ) {
				$this -> _removeFile( Mage :: getSingleton( 'catalog/product_media_config' ) -> getMediaPath( $image['file'] ) );
			} 
			$product -> delete();
			return true;
		} 
		
		if ( empty( $importData['type'] ) || !isset( $productTypes[strtolower( $importData['type'] )] ) ) {
			$value = isset( $importData['type'] ) ? $importData['type'] : '';
			$message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, is not valid value "%s" for field "%s"', $value, 'type' );
			Mage :: throwException( $message );
		} 
		$product -> setTypeId( $productTypes[strtolower( $importData['type'] )] );
		
		if ( empty( $importData['attribute_set'] ) || !isset( $productAttributeSets[$importData['attribute_set']] ) ) {
			$value = isset( $importData['attribute_set'] ) ? $importData['attribute_set'] : '';
			$message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, is not valid value "%s" for field "%s"', $value, 'attribute_set' );
			Mage :: throwException( $message );
		} 
		$product -> setAttributeSetId( $productAttributeSets[$importData['attribute_set']] );
		
		foreach ( $this -> _requiredFields as $field ) {
			$attribute = $this -> getAttribute( $field );
			if ( !isset( $importData[$field] ) && $attribute && $attribute -> getIsRequired() ) {
				$message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, required field "%s" for new products not defined', $field );
				Mage :: throwException( $message );
			} 
		} 
		/*
		if ( $importData['type'] == 'configurable' ) {
			$product -> setCanSaveConfigurableAttributes( true );
			$configAttributeCodes = $this -> userCSVDataAsArray( $importData['config_attributes'] );
			$usingAttributeIds = array();
			
			foreach( $configAttributeCodes as $attributeCode ) {
				$attribute = $product -> getResource() -> getAttribute( $attributeCode );
				if ( $product -> getTypeInstance() -> canUseAttribute( $attribute ) ) {
					if ( $new ) { // fix for duplicating attributes error
						$usingAttributeIds[] = $attribute -> getAttributeId();
					} 
				} 
			} 
			if ( !empty( $usingAttributeIds ) ) {
				$product -> getTypeInstance() -> setUsedProductAttributeIds( $usingAttributeIds );
				$product -> setConfigurableAttributesData( $product -> getTypeInstance() -> getConfigurableAttributesAsArray() );
				$product -> setCanSaveConfigurableAttributes( true );
				$product -> setCanSaveCustomOptions( true );
			} 
			if ( isset( $importData['associated'] ) ) {
				$product -> setConfigurableProductsData( $this -> skusToIds( $importData['associated'], $product ) );
			} 
		} 
		*/
		$currentproducttype = $importData['type'];
		
		if ($importData['type'] == 'configurable') {
			
			$product->setCanSaveConfigurableAttributes(true);
			$configAttributeCodes = $this->userCSVDataAsArray($importData['config_attributes']);
			$usingAttributeIds = array();

			/***
			* Check the product's super attributes (see catalog_product_super_attribute table), and make a determination that way.
			**/
			$cspa  = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
			$attr_codes = array();
			if(isset($cspa) && !empty($cspa)){ //found attributes
				foreach($cspa as $cs_attr){
				//$attr_codes[$cs_attr['attribute_id']] = $cs_attr['attribute_code'];
					$attr_codes[] = $cs_attr['attribute_id'];
				}
			}


			foreach($configAttributeCodes as $attributeCode) {
				$attribute = $product->getResource()->getAttribute($attributeCode);
				if ($product->getTypeInstance()->canUseAttribute($attribute)) {
					//if (!in_array($attributeCode,$attr_codes)) { // fix for duplicating attributes error
					if ($new) { // fix for duplicating attributes error // <---------- this must be true to fill $usingAttributes
						$usingAttributeIds[] = $attribute->getAttributeId();
					}
				}
			}
			if (!empty($usingAttributeIds)) {
				$product->getTypeInstance()->setUsedProductAttributeIds($usingAttributeIds);
				$product->setConfigurableAttributesData($product->getTypeInstance()->getConfigurableAttributesAsArray());
				$product->setCanSaveConfigurableAttributes(true);
				$product->setCanSaveCustomOptions(true);
			}
			if (isset($importData['associated'])) {
				$product->setConfigurableProductsData($this->skusToIds($importData['associated'], $product));
			}
		}
		
		//THIS IS FOR BUNDLE PRODUCTS
		if ($importData['type'] == 'bundle') {
			if ($new) {
				$optionscount=0;
				$items = array();
				//THIS IS FOR BUNDLE OPTIONS
				$commadelimiteddata = explode('|',$importData['bundle_options']);
				foreach ($commadelimiteddata as $data) {
					$configBundleOptionsCodes = $this->userCSVDataAsArray($data);
					$items[$optionscount]['title'] = $configBundleOptionsCodes[0];
					$items[$optionscount]['type'] = $configBundleOptionsCodes[1];
					$items[$optionscount]['required'] = $configBundleOptionsCodes[2];
					$items[$optionscount]['position'] = $configBundleOptionsCodes[3];
					$items[$optionscount]['delete'] = 0;
					$optionscount+=1;
					
					
					if ($items) {
							$product->setBundleOptionsData($items);
					}
					$options_id = $product->getOptionId();
					$selections = array();
					$bundleConfigData = array();
					$optionscountselection=0;
					//THIS IS FOR BUNDLE SELECTIONS
					$commadelimiteddataselections = explode('|',$importData['bundle_selections']);
					foreach ($commadelimiteddataselections as $selection) {
						$configBundleSelectionCodes = $this->userCSVDataAsArray($selection);
						$selectionscount=0;
						foreach ($configBundleSelectionCodes as $selectionItem) {
							$bundleConfigData = explode(':',$selectionItem);
							$selections[$optionscountselection][$selectionscount]['option_id'] = $options_id;
							$selections[$optionscountselection][$selectionscount]['product_id'] = $product->getIdBySku($bundleConfigData[0]);
              $selections[$optionscountselection][$selectionscount]['selection_price_value'] = $bundleConfigData[1];
							if(isset($bundleConfigData) && isset($bundleConfigData[2]) && $bundleConfigData[2] != '') {
							$selections[$optionscountselection][$selectionscount]['selection_qty'] = $bundleConfigData[2];
							$selections[$optionscountselection][$selectionscount]['selection_can_change_qty'] = $bundleConfigData[3];
							}
							$selections[$optionscountselection][$selectionscount]['delete'] = 0;
							$selectionscount+=1;
						}
						$optionscountselection+=1;
					}
					if ($selections) {
							$product->setBundleSelectionsData($selections);
					}
				
				}
				

        if ($product->getPriceType() == '0') {
            $product->setCanSaveCustomOptions(true);
            if ($customOptions = $product->getProductOptions()) {
                foreach ($customOptions as $key => $customOption) {
                    $customOptions[$key]['is_delete'] = 1;
                }
                $product->setProductOptions($customOptions);
            }
        }

        $product->setCanSaveBundleSelections();
			}
		}
		if ( isset( $importData['related'] ) ) {
			$linkIds = $this -> skusToIds( $importData['related'], $product );
			if ( !empty( $linkIds ) ) {
				$product -> setRelatedLinkData( $linkIds );
			} 
		} 

		if ( isset( $importData['upsell'] ) ) {
			$linkIds = $this -> skusToIds( $importData['upsell'], $product );
			if ( !empty( $linkIds ) ) {
				$product -> setUpSellLinkData( $linkIds );
			} 
		} 

		if ( isset( $importData['crosssell'] ) ) {
			$linkIds = $this -> skusToIds( $importData['crosssell'], $product );
			if ( !empty( $linkIds ) ) {
				$product -> setCrossSellLinkData( $linkIds );
			} 
		} 

		if ( isset( $importData['grouped'] ) ) {
			$linkIds = $this -> skusToIds( $importData['grouped'], $product );
			if ( !empty( $linkIds ) ) {
				$product -> setGroupedLinkData( $linkIds );
			} 
		} 

		if ( isset( $importData['category_ids'] ) ) {
			$product -> setCategoryIds( $importData['category_ids'] );
		} 

		//Tier Prices
		if( isset($importData['tier_prices']) && !empty($importData['tier_prices']) ) {
			$this->_editTierPrices($product, $importData['tier_prices']);
		}
		
		
		if ( isset( $importData['categories'] ) ) {
			if ( isset( $importData['store'] ) ) {
				$cat_store = $this -> _stores[$importData['store']];
			} else {
				$message = Mage :: helper( 'catalog' ) -> __( 'Skip import row, required field "store" for new products not defined', $field );
				Mage :: throwException( $message );
			} 
			$categoryIds = $this -> _addCategories( $importData['categories'], $cat_store );
			if ( $categoryIds ) {
				$product -> setCategoryIds( $categoryIds );
			} 
		} 
		
		foreach ( $this -> _ignoreFields as $field ) {
			if ( isset( $importData[$field] ) ) {
				unset( $importData[$field] );
			} 
		} 
		
		if ( $store -> getId() != 0 ) {
			$websiteIds = $product -> getWebsiteIds();
			if ( !is_array( $websiteIds ) ) {
				$websiteIds = array();
			} 
			if ( !in_array( $store -> getWebsiteId(), $websiteIds ) ) {
				$websiteIds[] = $store -> getWebsiteId();
			} 
			$product -> setWebsiteIds( $websiteIds );
		} 
		
		if ( isset( $importData['websites'] ) ) {
			$websiteIds = $product -> getWebsiteIds();
			if ( !is_array( $websiteIds ) ) {
				$websiteIds = array();
			} 
			$websiteCodes = explode( ',', $importData['websites'] );
			foreach ( $websiteCodes as $websiteCode ) {
				try {
					$website = Mage :: app() -> getWebsite( trim( $websiteCode ) );
					if ( !in_array( $website -> getId(), $websiteIds ) ) {
						$websiteIds[] = $website -> getId();
					} 
				} 
				catch ( Exception $e ) {
				} 
			} 
			$product -> setWebsiteIds( $websiteIds );
			unset( $websiteIds );
		} 
		
		$custom_options = array();

		foreach ( $importData as $field => $value ) {
			//if ( in_array( $field, $this -> _inventoryFields ) ) { 
				//continue;
			//} 
			if ( in_array( $field, $this -> _imageFields ) ) {
				continue;
			} 
			
			$attribute = $this -> getAttribute( $field );
			if ( !$attribute ) {
			/* CUSTOM OPTION CODE */
if(strpos($field,':')!==FALSE && strlen($value)) {
   $values=explode('|',$value);
   if(count($values)>0) {
      @list($title,$type,$is_required,$sort_order) = explode(':',$field);
      $title = ucfirst(str_replace('_',' ',$title));
      $custom_options[] = array(
         'is_delete'=>0,
         'title'=>$title,
         'previous_group'=>'',
         'previous_type'=>'',
         'type'=>$type,
         'is_require'=>$is_required,
         'sort_order'=>$sort_order,
         'values'=>array()
      );
      foreach($values as $v) {
         $parts = explode(':',$v);
         $title = $parts[0];
         if(count($parts)>1) {
            $price_type = $parts[1];
         } else {
            $price_type = 'fixed';
         }
         if(count($parts)>2) {
            $price = $parts[2];
         } else {
            $price =0;
         }
         if(count($parts)>3) {
            $sku = $parts[3];
         } else {
            $sku='';
         }
         if(count($parts)>4) {
            $sort_order = $parts[4];
         } else {
            $sort_order = 0;
         }
     if(count($parts)>5) {
            $file_extension = $parts[5];
         } else {
            $file_extension = '';
         }
		 if(count($parts)>6) {
            $image_size_x = $parts[6];
         } else {
            $image_size_x = '';
         }
		 if(count($parts)>7) {
            $image_size_y = $parts[7];
         } else {
            $image_size_y = '';
         }
         switch($type) {
            case 'file':
             $custom_options[count($custom_options) - 1]['price_type'] = $price_type;
             $custom_options[count($custom_options) - 1]['price'] = $price;
             $custom_options[count($custom_options) - 1]['sku'] = $sku;
             $custom_options[count($custom_options) - 1]['file_extension'] = $file_extension;
			 $custom_options[count($custom_options) - 1]['image_size_x'] = $image_size_x;
           	 $custom_options[count($custom_options) - 1]['image_size_y'] = $image_size_y;
			   break;
               
            case 'field':
            case 'area':
               $custom_options[count($custom_options) - 1]['max_characters'] = $sort_order;
               /* NO BREAK */
               
            case 'date':
            case 'date_time':
            case 'time':
               $custom_options[count($custom_options) - 1]['price_type'] = $price_type;
               $custom_options[count($custom_options) - 1]['price'] = $price;
               $custom_options[count($custom_options) - 1]['sku'] = $sku;
               break;
                                          
            case 'drop_down':
            case 'radio':
            case 'checkbox':
            case 'multiple':
            default:
               $custom_options[count($custom_options) - 1]['values'][]=array(
                  'is_delete'=>0,
                  'title'=>$title,
                  'option_type_id'=>-1,
                  'price_type'=>$price_type,
                  'price'=>$price,
                  'sku'=>$sku,
                  'sort_order'=>$sort_order,
               );
               break;
         }
      }
   }
}
/* END CUSTOM OPTION CODE */
				continue;
			} 

			$isArray = false;
			$setValue = $value;
			
			if ( $attribute -> getFrontendInput() == 'multiselect' ) {
				$value = explode( self :: MULTI_DELIMITER, $value );
				$isArray = true;
				$setValue = array();
			} 
			
			if ( $value && $attribute -> getBackendType() == 'decimal' ) {
				$setValue = $this -> getNumber( $value );
			} 
			
			if ( $attribute -> usesSource() ) {
				$options = $attribute -> getSource() -> getAllOptions( false );
				
				if ( $isArray ) {
					foreach ( $options as $item ) {
						if ( in_array( $item['label'], $value ) ) {
							$setValue[] = $item['value'];
						} 
					} 
				} 
				else {
					$setValue = null;
					foreach ( $options as $item ) {
						if ( $item['label'] == $value ) {
							$setValue = $item['value'];
						} 
					} 
				} 
			} 
			
			$product -> setData( $field, $setValue );
		} 
		
		if ( !$product -> getVisibility() ) {
			$product -> setVisibility( Mage_Catalog_Model_Product_Visibility :: VISIBILITY_NOT_VISIBLE );
		} 
		
		$stockData = array();
		$inventoryFields = isset($this->_inventoryFieldsProductTypes[$product->getTypeId()])
			? $this->_inventoryFieldsProductTypes[$product->getTypeId()]
			: array(); 
		foreach ( $inventoryFields as $field ) {
			if ( isset( $importData[$field] ) ) {
				if ( in_array( $field, $this -> _toNumber ) ) {
					$stockData[$field] = $this -> getNumber( $importData[$field] );
				} 
				else {
					$stockData[$field] = $importData[$field];
				} 
			} 
		} 
		$product -> setStockData( $stockData );
		
		$imageData = array();
		foreach ( $this -> _imageFields as $field ) {
			if ( !empty( $importData[$field] ) && $importData[$field] != 'no_selection' ) {
				if ( !isset( $imageData[$importData[$field]] ) ) {
					$imageData[$importData[$field]] = array();
				} 
				$imageData[$importData[$field]][] = $field;
			} 
		} 
		if ($new) { //starts CHECK FOR IF NEW PRODUCT
		foreach ( $imageData as $file => $fields ) {
			try {
				$product -> addImageToMediaGallery( Mage :: getBaseDir( 'media' ) . DS . 'import/' . $file, $fields, false );
			} 
			catch ( Exception $e ) {
			} 
		} 
		
		if ( !empty( $importData['gallery'] ) ) {
			$galleryData = explode( ',', $importData["gallery"] );
			foreach( $galleryData as $gallery_img ) {
				try {
					$product -> addImageToMediaGallery( Mage :: getBaseDir( 'media' ) . DS . 'import' . $gallery_img, null, false, false );
				} 
				catch ( Exception $e ) {
				} 
			} 
		} 
		
		}//ENDS CHECK FOR IF NEW PRODUCT
		$product -> setIsMassupdate( true );
		$product -> setExcludeUrlRewrite( true );
		
		$product -> save();
		/* Remove existing custom options attached to the product */
foreach ($product->getOptions() as $o) {
   $o->getValueInstance()->deleteValue($o->getId());
   $o->deletePrices($o->getId());
   $o->deleteTitles($o->getId());
   $o->delete();
}

/* Add the custom options specified in the CSV import file */
if(count($custom_options)) {
   foreach($custom_options as $option) {
      try {
        $opt = Mage::getModel('catalog/product_option');
        $opt->setProduct($product);
        $opt->addOption($option);
        $opt->saveOptions();
      }
      catch (Exception $e) {}
   }
}

if($iscustomoptions == "true") {
######### CUSTOM QUERY FIX FOR DISAPPEARING OPTIONS ################# 
// fetch write database connection that is used in Mage_Core module 

		if($currentproducttype == "simple") {
		$prefix = Mage::getConfig()->getNode('global/resources/db/table_prefix'); 
		$fixOptions = Mage::getSingleton('core/resource')->getConnection('core_write'); 
		// now $write is an instance of Zend_Db_Adapter_Abstract 
		$fixOptions->query("UPDATE ".$prefix."catalog_product_entity SET has_options = 1 WHERE type_id = 'simple' AND entity_id IN (SELECT distinct(product_id) FROM ".$prefix."catalog_product_option)"); 
		
		} else if ($currentproducttype == "configurable") {
		$prefix = Mage::getConfig()->getNode('global/resources/db/table_prefix'); 
		$fixOptions = Mage::getSingleton('core/resource')->getConnection('core_write'); 
		// now $write is an instance of Zend_Db_Adapter_Abstract 
		$fixOptions->query("UPDATE ".$prefix."catalog_product_entity SET has_options = 1 WHERE type_id = 'configurable' AND entity_id IN (SELECT distinct(product_id) FROM ".$prefix."catalog_product_option)"); 
		}
}
		return true;
	} 
	/**
	 * Edit tier prices
	 * 
	 * Uses a pipe-delimited string of qty:price to set tiers for the product row and appends.
	 * Removes if REMOVE is present.
	 * 
	 * @todo Prevent duplicate tiers (by qty) being set
	 * @internal Magento will save duplicate tiers; no enforcing unique tiers by qty, so we have to do this manually
	 * @param Mage_Catalog_Model_Product $product Current product row
	 * @param string $tier_prices_field Pipe-separated in the form of qty:price (e.g. 0=250=12.75|0=500=12.00)
	 */	
	private function _editTierPrices(&$product, $tier_prices_field = false)
	{
		if (($tier_prices_field) && !empty($tier_prices_field)) {
            
            if(trim($tier_prices_field) == 'REMOVE'){
            
                $product->setTierPrice(array());
            
            } else {
                
                
                //get current product tier prices
                $existing_tps = $product->getTierPrice();
                
                $etp_lookup = array();
                //make a lookup array to prevent dup tiers by qty
                foreach($existing_tps as $key => $etp){
                    $etp_lookup[intval($etp['price_qty'])] = $key;
                }
                
                //parse incoming tier prices string
                $incoming_tierps = explode('|',$tier_prices_field);
								$tps_toAdd = array();  
								$tierpricecount=0;              
							foreach($incoming_tierps as $tier_str){
										//echo "t: " . $tier_str;
                    if (empty($tier_str)) continue;
                    
                    $tmp = array();
                    $tmp = explode('=',$tier_str);
                    
                    if ($tmp[1] == 0 && $tmp[2] == 0) continue;
										//echo ('adding tier');
                    //print_r($tmp);
                    $tps_toAdd[$tierpricecount] = array(
                                        'website_id' => 0, // !!!! this is hard-coded for now
                                        'cust_group' => $tmp[0], // !!! so is this
                                        'price_qty' => $tmp[1],
                                        'price' => $tmp[2],
                                        'delete' => ''
                                    );
                                    
                    //drop any existing tier values by qty
                    if(isset($etp_lookup[intval($tmp[1])])){
                        unset($existing_tps[$etp_lookup[intval($tmp[1])]]);
                    }
                    $tierpricecount++;
                }

                //combine array
                $tps_toAdd =  array_merge($existing_tps, $tps_toAdd);
               
							 	//print_r($tps_toAdd);
                //save it
                $product->setTierPrice($tps_toAdd);
            }
            
        }
	}

	
	protected function userCSVDataAsArray( $data )
	{
		return explode( ',', str_replace( " ", "", $data ) );
	} 
	
	protected function skusToIds( $userData, $product )
	{
		$productIds = array();
		foreach ( $this -> userCSVDataAsArray( $userData ) as $oneSku ) {
			if ( ( $a_sku = ( int )$product -> getIdBySku( $oneSku ) ) > 0 ) {
				parse_str( "position=", $productIds[$a_sku] );
			} 
		} 
		return $productIds;
	} 
	
	protected $_categoryCache = array();

	
	protected function _addCategories( $categories, $store )
	{
		// $rootId = $store->getRootCategoryId();
		// $rootId = Mage::app()->getStore()->getRootCategoryId();
		$rootId = 2; // our store's root category id
		if ( !$rootId ) {
			return array();
		} 
		$rootPath = '1/' . $rootId;
		if ( empty( $this -> _categoryCache[$store -> getId()] ) ) {
			$collection = Mage :: getModel( 'catalog/category' ) -> getCollection()
			 -> setStore( $store )
			 -> addAttributeToSelect( 'name' );
			$collection -> getSelect() -> where( "path like '" . $rootPath . "/%'" );
			
			foreach ( $collection as $cat ) {
				try {
					$pathArr = explode( '/', $cat -> getPath() );
					$namePath = '';
					for ( $i = 2, $l = sizeof( $pathArr ); $i < $l; $i++ ) {
						$name = $collection -> getItemById( $pathArr[$i] ) -> getName();
						$namePath .= ( empty( $namePath ) ? '' : '/' ) . trim( $name );
					} 
					$cat -> setNamePath( $namePath );
				} 
				catch ( Exception $e ) {
					echo "ERROR: Cat - ";
					print_r( $cat );
					continue;
				} 
			} 
			
			$cache = array();
			foreach ( $collection as $cat ) {
				$cache[strtolower( $cat -> getNamePath() )] = $cat;
				$cat -> unsNamePath();
			} 
			$this -> _categoryCache[$store -> getId()] = $cache;
		} 
		$cache = &$this -> _categoryCache[$store -> getId()];
		
		$catIds = array();
		foreach ( explode( ',', $categories ) as $categoryPathStr ) {
			$categoryPathStr = preg_replace( '#s*/s*#', '/', trim( $categoryPathStr ) );
			if ( !empty( $cache[$categoryPathStr] ) ) {
				$catIds[] = $cache[$categoryPathStr] -> getId();
				continue;
			} 
			$path = $rootPath;
			$namePath = '';
			foreach ( explode( '/', $categoryPathStr ) as $catName ) {
				$namePath .= ( empty( $namePath ) ? '' : '/' ) . strtolower( $catName );
				if ( empty( $cache[$namePath] ) ) {
					$cat = Mage :: getModel( 'catalog/category' )
					 -> setStoreId( $store -> getId() )
					 -> setPath( $path )
					 -> setName( $catName )
					 -> setIsActive( 1 )
					 -> save();
					$cache[$namePath] = $cat;
				} 
				$catId = $cache[$namePath] -> getId();
				$path .= '/' . $catId;
			} 
			if ( $catId ) {
				$catIds[] = $catId;
			} 
		} 
		return join( ',', $catIds );
	} 
	
	protected function _removeFile( $file )
	{
		if ( file_exists( $file ) ) {
			if ( unlink( $file ) ) {
				return true;
			} 
		} 
		return false;
	} 
}