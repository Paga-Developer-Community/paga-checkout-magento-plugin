<?php

namespace Magento\PagaCheckout\Controller\Checkout;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Refresh extends Action {

    public function execute() {
        return $this->getResponse()->representJson(
                        $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode(['success' => true])
        );
    }

}
