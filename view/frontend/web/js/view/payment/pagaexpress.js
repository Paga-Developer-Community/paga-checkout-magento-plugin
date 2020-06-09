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
                component: 'Magento_PagaCheckout/js/view/payment/method-renderer/pagaexpress-method'
            }
        );        
        return Component.extend({});
    }
);