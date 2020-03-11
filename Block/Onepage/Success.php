<?php

namespace Magento\ExpressCheckout\Block\Onepage;

class Success extends \Magento\Checkout\Block\Onepage\Success {

    protected function _construct() {
        $this->setModuleName('Magento_Checkout');
        parent::_construct();
    }

    public function getUrl($route = '', $params = array()) {
        if (empty($route))
            return $this->_urlBuilder->getUrl(null, ['_secure' => true]);

        return parent::getUrl($route, $params);
    }

}
