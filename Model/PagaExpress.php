<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ExpressCheckout\Model;



/**
 * Pay In Store payment method model
 */
class PagaExpress extends \Magento\Payment\Model\Method\AbstractMethod
{

    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'pagaexpress';

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;
    
    
    
     protected $_isGateway = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */

    /**
     * Payment Method feature
     *
     * @var bool
     */ 
    protected $_canOrder = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canAuthorize = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canCapture = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canCapturePartial = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canCaptureOnce = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canRefund = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canRefundInvoicePartial = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canVoid = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canUseInternal = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canUseCheckout = true;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_isInitializeNeeded = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canFetchTransactionInfo = false;

    /**
     * Payment Method feature
     *
     * @var bool
     */
    protected $_canReviewPayment = false;

    /**
     * TODO: whether a captured transaction may be voided by this gateway
     * This may happen when amount is captured, but not settled
     * @var bool
     */


    
  

}
