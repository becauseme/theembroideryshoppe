<?php
class Magecomp_Recaptcha_IndexController extends Mage_Core_Controller_Front_Action
{
	const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    const XML_PATH_EMAIL_TEMPLATE   = 'contacts/email/email_template';
    const XML_PATH_ENABLED          = 'contacts/contacts/enabled';

    public function preDispatch()
    {
        parent::preDispatch();

        if( !Mage::getStoreConfigFlag(self::XML_PATH_ENABLED) ) {
            $this->norouteAction();
        }
    }

    public function indexAction()
    {
        $this->loadLayout();


        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('catalog/session');
        $this->renderLayout();
    }
	public function saveAction()
	{
	    try {
            $nam = $this->getRequest()->getParam('name');
            $emails = $this->getRequest()->getParam('email');
            $telephone = $this->getRequest()->getParam('telephone');
            $cmt = $this->getRequest()->getParam('comment');
            $receiveEmail = Mage::getStoreConfig('trans_email/ident_general/email');
            $g_response = $this->getRequest()->getParam('g-recaptcha-response');

            if (isset($g_response) && !empty($g_response)):
                if (Mage::helper('recaptcha')->Validate_captcha($g_response)):
                    $post = $this->getRequest()->getPost();
        if ( $post ) {
            $translate = Mage::getSingleton('core/translate');
            /* @var $translate Mage_Core_Model_Translate */
            $translate->setTranslateInline(false);
            try {
                $postObject = new Varien_Object();
                $postObject->setData($post);

                $error = false;

                if (!Zend_Validate::is(trim($post['name']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['comment']) , 'NotEmpty')) {
                    $error = true;
                }

                if (!Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
                    $error = true;
                }
                if ($error) {
                    throw new Exception();
                }
                $mailTemplate = Mage::getModel('core/email_template');
                /* @var $mailTemplate Mage_Core_Model_Email_Template */
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))
                    ->setReplyTo($post['email'])
                    ->sendTransactional(
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT),
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                return $this->_redirectReferer();

                return;
            } catch (Exception $e) {
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                return $this->_redirectReferer();
                
            }

        } else {
            $this->_redirect('*/*/');
        }
                else:
                    echo Mage::getSingleton('core/session')->addError('Please click on the reCAPTCHA box.');
                    return $this->_redirectReferer();
                endif;
            else:
                echo Mage::getSingleton('core/session')->addError('Please click on the reCAPTCHA box.');
                return $this->_redirectReferer();
            endif;
        }
        catch (Exception $e){
	        Mage::log("Captcha Error :".$e->getMessage(),null,"recaptcha.log");
        }
	}
}

?>