<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\ExpressCheckout\Model;

use \Magento\Checkout\Model\ConfigProviderInterface;
use \Magento\Customer\Helper\Session\CurrentCustomer;

class ConfigProvider implements ConfigProviderInterface {
    /**
     * @var string[]
     */

    /**
     * @var \Razorpay\Magento\Model\Config
     */
    protected $method;
    protected $currentCustomer;
    protected $paymentMethod;
    protected $isTestMode;
    protected $crypt;

    /**
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Url $urlBuilder
     */

    /**
     * @param \Magento\Payment\Helper\Data $paymentHelper
     */
    public function __construct(
    CurrentCustomer $currentCustomer, \Magento\ExpressCheckout\Model\PagaExpress $PaymentMethod, \Magento\Framework\Encryption\Encryptor $crypt
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->paymentMethod = $PaymentMethod;
        $this->isTestMode = (boolean) $this->paymentMethod->getConfigData('test_mode');
        $this->crypt = $crypt;
    }

    /**
     * @return array|void
     */
    public function getConfig() {

        $config = [
            'payment' => [
                'pagaexpress' => [
                    'public_key' => $this->getApiPublicKey(), // get from config 
                    'isCustomerLoggedIn' => $this->getIsCustomerLoggedIn(),
                    'apiUrl' => $this->getApiUrl()
                ],
            ],
        ];

        return $config;
    }

    private function getIsCustomerLoggedIn() {

        if (!$this->currentCustomer->getCustomerId()) {
            return false;
        }

        return true;
    }

    private function getApiUrl() {

        if ($this->isTestMode) {
            return "https://qa1.mypaga.com/checkout/";
        } else {

            return "https://www.mypaga.com/checkout/";
        }
    }

    private function getApiPublicKey() {
        if ($this->isTestMode) {
            return $this->paymentMethod->getConfigData('test_public_key');
        } else {
            return $this->paymentMethod->getConfigData('live_public_key');
        }
    }

}
