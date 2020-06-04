<?php

namespace Magento\PagaCheckout\Controller\Checkout;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Quote extends Action {

    protected $_checkoutSession;
    protected $_quote;
    protected $request;

    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\App\Request\Http $request
    ) {

        $this->_checkoutSession = $checkoutSession;

        $this->request = $request;


        parent::__construct($context);
    }

    public function execute() {
        $email = $this->request->getParam('email');
        if ($email) {
            $this->_loadQuote();
            $this->_quote->getBillingAddress()->setEmail($email)->save();
        }
    }

    protected function _loadQuote() {
        $this->_quote = $this->_checkoutSession->getQuote();
    }

}
