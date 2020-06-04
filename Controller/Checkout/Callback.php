<?php

namespace Magento\PagaCheckout\Controller\Checkout;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\PaymentException;

class Callback extends \Magento\Framework\App\Action\Action {

    protected $_paymentMethod;
    protected $_notification;

    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $_order;

    /**
     * @var \Magento\Sales\Model\OrderFactory
     */
    protected $_orderFactory;

    /**
     * @var Magento\Sales\Model\Order\Email\Sender\OrderSender
     */
    protected $_orderSender;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;
    protected $transactionFactory;
    protected $_objectManager;
    protected $pagaExpressHelper;
    protected $_quote;
    protected $_messageManager;
    protected $request;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager     
     * @param Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender
     * @param  \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
    \Magento\Framework\App\Action\Context $context, \Magento\Framework\ObjectManagerInterface $objectManager, \Magento\ExpressCheckout\Model\PagaExpress $paymentMethod, \Magento\Sales\Model\Order\Email\Sender\OrderSender $orderSender, \Magento\Checkout\Model\Session $checkoutSession, \Psr\Log\LoggerInterface $logger, \Magento\ExpressCheckout\Helper\Data $pagaExpressHelper, \Magento\Framework\DB\TransactionFactory $transactionFactory, \Magento\Framework\Message\ManagerInterface $messageManager, \Magento\Framework\App\Request\Http $request
    ) {
        $this->_paymentMethod = $paymentMethod;
        $this->_objectManager = $objectManager;
        $this->transactionFactory = $transactionFactory;
        $this->_orderSender = $orderSender;
        $this->_checkoutSession = $checkoutSession;
        $this->pagaExpressHelper = $pagaExpressHelper;
        $this->_logger = $logger;
        $this->_messageManager = $messageManager;
        $this->request = $request;


        parent::__construct($context);
    }

    public function execute() {

        $this->_logger->addDebug("Paga Express Checkout Callback");
        $this->_logger->addDebug(print_r($_REQUEST, true));


        $this->_loadQuote();
        $this->_verify();
    }

    private function _verify() {

        $success = false;
        $chargeReference = $this->request->getParam('charge_reference');
        $this->_quote->setPagaChargeReference($chargeReference)->save();
        $transactionStatus = $this->pagaExpressHelper->verifyTransaction($this->_quote);
        $this->_logger->addDebug("Verify response:...");
        $this->_logger->addDebug(print_r($transactionStatus, true));
        if (isset($transactionStatus->status_code)) { // status code not empty             
            if ($transactionStatus->status_code == 0 || $transactionStatus->status_code == null) {
                $this->_placeOrder();
                $this->_registerPaymentCapture();
                $this->_checkoutSession->setMinicartNeedsRefresh(true);
                $this->_redirect('checkout/onepage/success');
                $success = true;
                return $this;
            }
        }


        if (isset($transactionStatus->error)) {
            $this->_messageManager->addError((string) $transactionStatus->error);
        }

        if (!$success) {
            $this->_messageManager->addError("There was an error processing your payment. Please try again.");
            $this->_redirect('checkout/cart');
        }

        return $this;
    }

    protected function _loadQuote() {

        $this->_quote = $this->_checkoutSession->getQuote();

        if (!$this->_quote->getId()) {
            $this->_messageManager->addError("Could not load quote id:$quote_id");
            $this->_logger->addDebug("Could not find Magento quote with id $quote_id");
            $this->_logger->addDebug(print_r($this->request->getParams(), true));
            $this->_redirect('checkout/cart');
        }
    }

    public function _placeOrder() {

        $quote = $this->_quote;
        $this->_quote->getPayment()->importData(array('method' => 'pagaexpress'));

        try {
            //Convert Quote to Order                       
            $this->_quote->setCustomerIsGuest($this->pagaExpressHelper->getCustomerIsGuest());
            $this->_validateQuote();
            $quoteManagement = $this->getModel('\Magento\Quote\Model\QuoteManagement');
            $order = $quoteManagement->submit($this->_quote);
            $order->setEmailSent(0);


            /** @var $checkoutSession \Magento\Checkout\Model\Session */
            $checkoutSession = $this->getModel('Magento\Checkout\Model\Session');
            $checkoutSession->setQuoteId($this->_quote->getId());
            $checkoutSession->setLoadInactive(true);

            $checkoutSession->setLastOrderId($order->getId())
                    ->setLastSuccessQuoteId($this->_quote->getId())
                    ->setLastQuoteId($this->_quote->getId())
                    ->setLastRealOrderId($order->getIncrementId());

            $this->_quote->setIsActive(false)->save();

            $this->eventManager = $this->getModel('Magento\Framework\Event\ManagerInterface');
            $this->eventManager->dispatch('checkout_submit_all_after', ['order' => $order, 'quote' => $this->_quote]);
            $this->_order = $order;
        } catch (Exception $e) {
            //$this->_logger->addDebug($e);
        }
        return $this;
    }

    private function _validateQuote() {

        if (!$this->_quote->getCustomerEmail())
            $this->_quote->setCustomerEmail($this->_quote->getBillingAddress()->getEmail());

        if (!$this->_quote->getCustomerFirstname())
            $this->_quote->setCustomerFirstname($this->_quote->getBillingAddress()->getFirstname());

        if (!$this->_quote->getCustomerMiddlename())
            $this->_quote->setCustomerMiddlename($this->_quote->getBillingAddress()->getMiddlename());

        if (!$this->_quote->getCustomerLastname())
            $this->_quote->setCustomerLastname($this->_quote->getBillingAddress()->getLastname());
    }

    private function getModel($_model) {
        return $this->_objectManager->create($_model);
    }

    protected function _registerPaymentCapture() {

        $order = $this->_order;

        if ($order->getPayment()->getMethod() == $this->_paymentMethod->getCode()) {
            $qtys = [];
            foreach ($order->getAllItems() as $orderItem) {
                $qtys[$orderItem->getId()] = $orderItem->getQty();
            }

            $invoice = $order->prepareInvoice($qtys);
            $invoice->setRequestedCaptureCase(\Magento\Sales\Model\Order\Invoice::CAPTURE_ONLINE);
            $invoice->register();
            $transaction = $this->transactionFactory->create();
            $transaction->addObject($invoice)
                    ->addObject($invoice->getOrder())
                    ->save();

            if ($invoice && !$this->_order->getEmailSent()) {
                $this->_orderSender->send($this->_order);
                $this->_order->addStatusHistoryComment(
                        __('You notified customer about invoice #%1.', $invoice->getIncrementId())
                )->setIsCustomerNotified(
                        true
                )->save();
            }
        }
    }

}
