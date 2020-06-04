<?php

namespace Magento\PagaCheckout\Helper;

use \Magento\Customer\Helper\Session\CurrentCustomer;

class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    protected $logger;
    protected $orderNotifierHelper;
    protected $orderNotifierEntryFactory;
    protected $catalogImageHelper;
    protected $orderNotifierApiHelper;
    protected $backendSession;
    protected $checkoutSession;
    protected $messageManager;
    protected $imageBlock;
    protected $mediaUrl;
    protected $scopeConfig;
    protected $storeManager;
    protected $_resourceConfig;
    protected $currentCustomer;

    public function __construct(
    \Psr\Log\LoggerInterface $logger, \Magento\Store\Model\StoreManagerInterface $storeManager, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Backend\Model\Session $backendSession, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Config\Model\ResourceModel\Config $resourceConfig, CurrentCustomer $currentCustomer
    ) {

        $this->logger = $logger;
        $this->messageManager = $messageManager;
        $this->backendSession = $backendSession;
        $this->scopeConfig = $scopeConfig;
        $this->_resourceConfig = $resourceConfig;
        $this->checkoutSession = $checkoutSession;
        $this->storeManager = $storeManager;
        $this->currentCustomer = $currentCustomer;
    }

    function getPublicKey() {
        if ($this->getIsTestMode()) {
            return trim($this->scopeConfig->getValue('payment/pagaexpress/test_public_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        } else {
            return trim($this->scopeConfig->getValue('payment/pagaexpress/live_public_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        }
    }

    function getSecretKey() {
        if ($this->getIsTestMode()) {
            return trim($this->scopeConfig->getValue('payment/pagaexpress/test_secret_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        } else {
            return trim($this->scopeConfig->getValue('payment/pagaexpress/live_secret_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE));
        }
    }

    private function getIsTestMode() {
        if ($this->scopeConfig->getValue('payment/pagaexpress/test_mode', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            return true;
        } else {
            return false;
        }
    }

    function verifyTransaction($quote) {



        if (!$quote->getId()) {
            return;
        }

        if ($this->getIsTestMode()) {
            $link = "https://qa1.";
        } else {
            $link = "https://www.";
        }

        $ch = curl_init();
        $transactionStatus = new \stdClass();

        $obj = array();
        $obj['publicKey'] = $this->getPublicKey();
        $obj['secretKey'] = $this->getSecretKey();
        $obj['paymentReference'] = $quote->getPagaChargeReference();
        $obj['amount'] = $quote->getGrandTotal();
        $obj['currency'] = $quote->getBaseCurrencyCode();

        $this->logger->addDebug(print_r($obj, true));
        $request = json_encode($obj);
        //echo $request;
        // set url
        curl_setopt($ch, CURLOPT_URL, $link . "mypaga.com/checkout/transaction/verify");

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'content-type: application/json'
        ));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);

        // Make sure CURL_SSLVERSION_TLSv1_2 is defined as 6
        // cURL must be able to use TLSv1.2 to connect
        // to Paga servers
        if (!defined('CURL_SSLVERSION_TLSv1_2')) {
            define('CURL_SSLVERSION_TLSv1_2', 6);
        }
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);

        // exec the cURL
        $response = curl_exec($ch);
        //echo $response;
        // should be 0        
        if (curl_errno($ch)) {
            // curl ended with an error
            $transactionStatus->error = "cURL said:" . curl_error($ch);
            curl_close($ch);
        } else {

            //close connection
            curl_close($ch);
//var_dump($response);exit;
            // Then, after your curl_exec call:
            $body = json_decode($response);
            $this->logger->addDebug(print_r($body, true));
            if ($body->status_code != 0 || $body->status_code != null) {
                // Paga has an error message for us
                $transactionStatus->error = "Paga API said: Invalid Transaction ";
            } else {
                // get body returned by Paga API
                $transactionStatus = $body;
            }
        }

        return $transactionStatus;
    }

    public function getCustomerIsGuest() {

        if ($this->currentCustomer->getCustomerId()) {
            return false;
        }

        return true;
    }

}
