define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'pagaexpress',
                component: 'Paga_ExpressCheckout/js/view/payment/method-renderer/pagaexpress-method'
            }
        );        
        return Component.extend({});
    }
);