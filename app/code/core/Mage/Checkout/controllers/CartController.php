<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Checkout
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Shopping cart controller
 */
class Mage_Checkout_CartController extends Mage_Core_Controller_Front_Action
{
    /**
     * Action list where need check enabled cookie
     *
     * @var array
     */
    protected $_cookieCheckActions = array('add');

    /**
     * Retrieve shopping cart model object
     *
     * @return Mage_Checkout_Model_Cart
     */
    protected function _getCart()
    {
        return Mage::getSingleton('checkout/cart');
    }

    /**
     * Get checkout session model instance
     *
     * @return Mage_Checkout_Model_Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('checkout/session');
    }

    /**
     * Get current active quote instance
     *
     * @return Mage_Sales_Model_Quote
     */
    protected function _getQuote()
    {
        return $this->_getCart()->getQuote();
    }

    /**
     * Set back redirect url to response
     *
     * @return Mage_Checkout_CartController
     */
    protected function _goBack()
    {
        if (!Mage::getStoreConfig('checkout/cart/redirect_to_cart')
            && !$this->getRequest()->getParam('in_cart')
            && $backUrl = $this->_getRefererUrl()) {

            $this->getResponse()->setRedirect($backUrl);
        } else {
            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {
                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());
            }
            $this->_redirect('checkout/cart');
        }
        return $this;
    }

    /**
     * Initialize product instance from request data
     *
     * @return Mage_Catalog_Model_Product || false
     */
    protected function _initProduct()
    {
        $productId = (int) $this->getRequest()->getParam('product');
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }

    /**
     * Shopping cart display action
     */
    public function indexAction()
    {
        $cart = $this->_getCart();
        if ($cart->getQuote()->getItemsCount()) {
            $cart->init();
            $cart->save();

            if (!$this->_getQuote()->validateMinimumAmount()) {
                $warning = Mage::getStoreConfig('sales/minimum_order/description');
                $cart->getCheckoutSession()->addNotice($warning);
            }
        }

        foreach ($cart->getQuote()->getMessages() as $message) {
            if ($message) {
                $cart->getCheckoutSession()->addMessage($message);
            }
        }

        /**
         * if customer enteres shopping cart we should mark quote
         * as modified bc he can has checkout page in another window.
         */
        $this->_getSession()->setCartWasUpdated(true);

        Varien_Profiler::start(__METHOD__ . 'cart_display');
        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        $this->_initLayoutMessages('catalog/session');
        $this->getLayout()->getBlock('head')->setTitle($this->__('Shopping Cart'));
        $this->renderLayout();
        Varien_Profiler::stop(__METHOD__ . 'cart_display');
    }

    /**
     * Add product to shopping cart action
     */
  
   public function olderaddAction()
    {
	    $cart   = $this->_getCart();
        $params = $this->getRequest()->getParams();

        $product= $this->_initProduct();
        $related= $this->getRequest()->getParam('related_product');
		$selectlogo= $this->getRequest()->getParam('select_logo');

        /**
         * Check product availability
         */
        if (!$product) {
            $this->_goBack();
            return;
        }


        try {
		
	
         
		/*print_r($product);
		exit;*/
			/*echo "<pre>";
			print_r($params);
			die();*/
			$cart->addProduct($product, $params);
			
			
		
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            $cart->save();

            $this->_getSession()->setCartWasUpdated(true);

            /**
             * @todo remove wishlist observer processAddToCart
             */
            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );
            $message = $this->__('%s was successfully added to your shopping cart.', $product->getName());
            if (!$this->_getSession()->getNoCartRedirect(true)) {
                $this->_getSession()->addSuccess($message);
                $this->_goBack();
            }
        }
        catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice($e->getMessage());
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError($message);
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        }
        catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Can not add item to shopping cart'));
            $this->_goBack();
        }
    }
  
  public function addAction()
    {
	    $logoc='';
		$cart   = $this->_getCart();
        $params1 = $this->getRequest()->getParams();

        $product= $this->_initProduct();
        $related= $this->getRequest()->getParam('related_product');
		 $selectlogo= $this->getRequest()->getParam('select_logo');
         $nameselectinfo= $this->getRequest()->getParam('nametextOpt');
         $phoneselectinfo= $this->getRequest()->getParam('phonetextOpt');
         $emailselectinfo= $this->getRequest()->getParam('emailtextOpt');
	
		//echo "<pre>";
		
		//print_r($params1);
		//die();	
        if (!$product) {
            $this->_goBack();
            return;
        }


        try {
		
			
		$colarray=array();
		
	
		 /****************/
        /* Custom Params*/
        /****************/
        //Create custom option
       /* $additionalOptions = array(array(
           'code' => 'my_code',
           'label' => 'This text is displayed through additional options',
           'value' =>  $this->getRequest()->getParam('textOpt')
        ));
        //Add Custom Option to product
        $product->addOption($cart->addOption(array(
            'code' => 'additional_options',
             'value' => serialize($additionalOptions)
        )));   */  
        /****************/
		
		//echo $param1['select_logo'];
		
		
		//$colorv= $params1['options']['4231'];
		  if(isset($params1['select_logo']))
		  $logoc=$params1['select_logo'];

		if(isset($params1['options']['size'])){
			//echo "<pre>";
		
		//print_r($params1);
		//die();
		 foreach($params1['options']['size'] as $key=>$sizeop)
			{
				if(is_array($sizeop))
				{
					foreach($sizeop as $optval=>$optqty){
					if($optqty!=''){
						$product= $this->_initProduct();
					//echo "$key=>option value".$optval;
					//echo "option qty".$optqty;
	$params = array(
        'product' => $product->getId(),
        'qty' => $optqty,
        'related_product' => null,
    	'select_logo'=>"{$logoc}" ,        
        'options' => array($key=>$optval)
	);
     if(isset($params1['nametextOpt'])){
          $customNameInfo=$params1['nametextOpt'];
          $params['additional_options']['customName']=$customNameInfo;
     }
        
      
        if(isset($params1['phonetextOpt'])){
          $customPhoneInfo=$params1['phonetextOpt'];
          $params['additional_options']['customPhone']=$customPhoneInfo;
        }
        if(isset($params1['emailtextOpt'])){
          $customEmailInfo=$params1['emailtextOpt'];
          $params['additional_options']['customEmail']=$customEmailInfo;
        }
	
	foreach($params1['options'] as $optcolkey=>$optcolval)
			{
				if($optcolkey!='size'){
				$colarray=array('colkey'=>$optcolkey,'colval'=>$optcolval);
				$params['options'][$optcolkey]=$optcolval;
				}

	}
/*echo "<pre>";

print_r($params);
die();
*/		
		$cart->addProduct($product, $params);	
		 
		
	
			
					
					}
					}
				}
			}
		/*echo "<pre>";
		print_r($params);
		die();*/
			
			}else{
			
	
					$params = array(
    'product' => $product->getId(),
    'qty' => $params1['qty'],
    'related_product' => null,
	'select_logo'=>"{$logoc}",
     'additional_options'=>$prodInfo,
	);
			/*echo "<pre>";
		print_r($params);
		die();*/
		
	foreach($params1['options'] as $optcolkey=>$optcolval)
			{
				if($optcolkey!='size'){
				$colarray=array('colkey'=>$optcolkey,'colval'=>$optcolval);
				$params['options'][$optcolkey]=$optcolval;
				}

	}
			$cart->addProduct($product, $params);	
		
			}
				
		Mage::getSingleton('customer/session')->setCartWasUpdated(true);
        $cart->save();
			
			//die();
			
			
			
		
            if (!empty($related)) {
                $cart->addProductsByIds(explode(',', $related));
            }

            

            $this->_getSession()->setCartWasUpdated(true);

          
            Mage::dispatchEvent('checkout_cart_add_product_complete',
                array('product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse())
            );
            $message = $this->__('%s was successfully added to your shopping cart.', $product->getName());
            if (!$this->_getSession()->getNoCartRedirect(true)) {
                $this->_getSession()->addSuccess($message);
                $this->_goBack();
            }
        }
        catch (Mage_Core_Exception $e) {
            if ($this->_getSession()->getUseNotice(true)) {
                $this->_getSession()->addNotice($e->getMessage());
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->_getSession()->addError($message);
                }
            }

            $url = $this->_getSession()->getRedirectUrl(true);
            if ($url) {
                $this->getResponse()->setRedirect($url);
            } else {
                $this->_redirectReferer(Mage::helper('checkout/cart')->getCartUrl());
            }
        }
        catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Can not add item to shopping cart'));
            $this->_goBack();
        }
    }
	
	

    public function addgroupAction()
    {
        $orderItemIds = $this->getRequest()->getParam('order_items', array());
        if (is_array($orderItemIds)) {
            $itemsCollection = Mage::getModel('sales/order_item')
                ->getCollection()
                ->addIdFilter($orderItemIds)
                ->load();
            /* @var $itemsCollection Mage_Sales_Model_Mysql4_Order_Item_Collection */
            $cart = $this->_getCart();
            foreach ($itemsCollection as $item) {
                try {
                    $cart->addOrderItem($item, 1);
                }
                catch (Mage_Core_Exception $e) {
                    if ($this->_getSession()->getUseNotice(true)) {
                        $this->_getSession()->addNotice($e->getMessage());
                    } else {
                        $this->_getSession()->addError($e->getMessage());
                    }
                }
                catch (Exception $e) {
                    $this->_getSession()->addException($e, $this->__('Can not add item to shopping cart'));
                    $this->_goBack();
                }
            }
            $cart->save();
            $this->_getSession()->setCartWasUpdated(true);
        }
        $this->_goBack();
    }

    /**
     * Update shoping cart data action
     */
    public function updatePostAction()
    {
        try {
            $cartData = $this->getRequest()->getParam('cart');
            if (is_array($cartData)) {
                $cart = $this->_getCart();
                $cart->updateItems($cartData)
                    ->save();
            }
            $this->_getSession()->setCartWasUpdated(true);
        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
            $this->_getSession()->addException($e, $this->__('Cannot update shopping cart'));
        }
        $this->_goBack();
    }

    /**
     * Delete shoping cart item action
     */
    public function deleteAction()
    {
        $id = (int) $this->getRequest()->getParam('id');
        if ($id) {
            try {
                $this->_getCart()->removeItem($id)
                  ->save();
            } catch (Exception $e) {
                $this->_getSession()->addError($this->__('Cannot remove item'));
            }
        }
        $this->_redirectReferer(Mage::getUrl('*/*'));
    }

    /**
     * Initialize shipping information
     */
    public function estimatePostAction()
    {
        $country    = (string) $this->getRequest()->getParam('country_id');
        $postcode   = (string) $this->getRequest()->getParam('estimate_postcode');
        $city       = (string) $this->getRequest()->getParam('estimate_city');
        $regionId   = (string) $this->getRequest()->getParam('region_id');
        $region     = (string) $this->getRequest()->getParam('region');

        $this->_getQuote()->getShippingAddress()
            ->setCountryId($country)
            ->setCity($city)
            ->setPostcode($postcode)
            ->setRegionId($regionId)
            ->setRegion($region)
            ->setCollectShippingRates(true);
        $this->_getQuote()->save();
        $this->_goBack();
    }

    public function estimateUpdatePostAction()
    {
        $code = (string) $this->getRequest()->getParam('estimate_method');
        if (!empty($code)) {
            $this->_getQuote()->getShippingAddress()->setShippingMethod($code)/*->collectTotals()*/->save();
        }
        $this->_goBack();
    }

    /**
     * Initialize coupon
     */
    public function couponPostAction()
    {
        /**
         * No reason continue with empty shopping cart
         */
        if (!$this->_getCart()->getQuote()->getItemsCount()) {
            $this->_goBack();
            return;
        }

        $couponCode = (string) $this->getRequest()->getParam('coupon_code');
        if ($this->getRequest()->getParam('remove') == 1) {
            $couponCode = '';
        }
        $oldCouponCode = $this->_getQuote()->getCouponCode();

        if (!strlen($couponCode) && !strlen($oldCouponCode)) {
            $this->_goBack();
            return;
        }

        try {
            $this->_getQuote()->getShippingAddress()->setCollectShippingRates(true);
            $this->_getQuote()->setCouponCode(strlen($couponCode) ? $couponCode : '')
                ->collectTotals()
                ->save();

            if ($couponCode) {
                if ($couponCode == $this->_getQuote()->getCouponCode()) {
                    $this->_getSession()->addSuccess(
                        $this->__('Coupon code "%s" was applied successfully.', Mage::helper('core')->htmlEscape($couponCode))
                    );
                }
                else {
                    $this->_getSession()->addError(
                        $this->__('Coupon code "%s" is not valid.', Mage::helper('core')->htmlEscape($couponCode))
                    );
                }
            } else {
                $this->_getSession()->addSuccess($this->__('Coupon code was canceled successfully.'));
            }

        }
        catch (Mage_Core_Exception $e) {
            $this->_getSession()->addError($e->getMessage());
        }
        catch (Exception $e) {
            $this->_getSession()->addError($this->__('Can not apply coupon code.'));
        }

        $this->_goBack();
    }
}