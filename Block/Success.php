<?php

namespace Magento\ExpressCheckout\Block;

use Magento\Checkout\Model\Session;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;

class Success extends \Magento\Framework\View\Element\Template {

    protected $_checkoutSession;
    protected $customerRepository;

    public function __construct(
    Context $context, Session $session, array $data = []
    ) {
        $this->_checkoutSession = $session;
        parent::__construct($context, $data);
    }

    public function isRefreshRequired() {
        return true;
        if ($this->_checkoutSession->getMinicartNeedsRefresh()) {
            $this->_checkoutSession->setMinicartNeedsRefresh(false);
            return true;
        } else {
            return false;
        }
    }

    public function getRefreshUrl() {
        return $this->getUrl('pagaexpress/checkout/refresh',['_secure' => true]);
    }

}
