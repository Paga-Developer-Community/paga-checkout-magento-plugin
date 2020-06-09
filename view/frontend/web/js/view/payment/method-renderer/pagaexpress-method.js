define([
    "Magento_Checkout/js/view/payment/default",
    "Magento_Checkout/js/model/quote",
    "jquery",
    "ko",
    "Magento_Checkout/js/action/select-payment-method",
    "Magento_Checkout/js/model/payment/additional-validators",
    "Magento_Checkout/js/action/set-payment-information",
    "mage/url",
    "Magento_Customer/js/model/customer",
    "Magento_Checkout/js/action/place-order",
    "Magento_Checkout/js/checkout-data",
    "Magento_Ui/js/model/messageList"
], function(
    Component,
    quote,
    $,
    ko,
    selectPaymentMethodAction,
    additionalValidators,
    setPaymentInformationAction,
    url,
    customer,
    placeOrderAction,
    checkoutData,
    messageList
) {
    console.log(url);
    console.log(url.build("pagaexpress/checkout/callback"));
    return Component.extend({
        defaults: {
            template: "Magento_PagaCheckout/payment/pagaexpress"
        },
        getMailingAddress: function() {
            return window.checkoutConfig.payment.checkmo.mailingAddress;
        },
        redirectAfterPlaceOrder: false,
        afterPlaceOrder: function(data, event) {
            var self = this;
            fullScreenLoader.startLoader();
            $.ajax({
                type: "POST",
                url: url.build("pagaexpress/checkout/callback"),
                success: function(response) {
                    fullScreenLoader.stopLoader();
                    if (response.success) {
                        this.pymbIframeSrc = response.iframeSrc;
                        self.renderIframe(response);
                    } else {
                        self.isPaymentProcessing.reject(response.message);
                    }
                },
                error: function(response) {
                    fullScreenLoader.stopLoader();
                    self.isPaymentProcessing.reject(response.message);
                }
            });
        },
        isPlaceOrderActionAllowed: ko.observable(
            quote.billingAddress() != null
        ),
        selectPaymentMethod: function() {
            selectPaymentMethodAction(this.getData());
            checkoutData.setSelectedPaymentMethod(this.item.method);
            this.renderPagaScript();
            return true;
        },
        renderPagaScript: function() {
            var checkoutConfig = window.checkoutConfig;
            var paymentData = quote.billingAddress();
            var pagaCheckoutConfiguration =
                checkoutConfig.payment.pagaexpress;
            if (checkoutConfig.isCustomerLoggedIn) {
                var customerData = checkoutConfig.customerData;
                paymentData.email = customerData.email;
            } else {
                var storageData = JSON.parse(
                    localStorage.getItem("mage-cache-storage")
                )["checkout-data"];
                paymentData.email = storageData.validatedEmailValue;
            }

            var scriptTag = document.createElement("script");
            scriptTag.type = "text/javascript";
            scriptTag.src = window.checkoutConfig.payment.pagaexpress.apiUrl;
            scriptTag.setAttribute(
                "data-public_key",
                window.checkoutConfig.payment.pagaexpress.public_key
            );
            scriptTag.setAttribute(
                "data-amount",
                quote.totals()["base_grand_total"]
            ); // quote total
            scriptTag.setAttribute(
                "data-payment_reference",
                quote.getQuoteId()
            );
            scriptTag.setAttribute(
                "data-charge_url",
                url.build("pagaexpress/checkout/callback")
            );
            scriptTag.setAttribute(
                "data-currency",
                quote.totals()["base_currency_code"]
            );
            scriptTag.setAttribute("data-email", paymentData.email);
            document.getElementById("PagaBtnWrapper").innerHTML = "";
            console.log(scriptTag);
            document.getElementById("PagaBtnWrapper").appendChild(scriptTag);
            console.log(
                document.getElementById("PagaBtnWrapper").appendChild(scriptTag)
            );
            this._updateQuote();
        },
        _updateQuote: function() {
            var checkoutConfig = window.checkoutConfig;
            var paymentData = quote.billingAddress();
            var pagaCheckoutConfiguration =
                checkoutConfig.payment.pagaexpress;
            console.log(pagaCheckoutConfiguration);
            if (checkoutConfig.isCustomerLoggedIn) {
                var customerData = checkoutConfig.customerData;
                console.log(customerData);
                paymentData.email = customerData.email;
                console.log(paymentData);
            } else {
                var storageData = JSON.parse(
                    localStorage.getItem("mage-cache-storage")
                )["checkout-data"];
                console.log(storageData);
                paymentData.email = storageData.validatedEmailValue;
            }
            $.post(url.build("pagaexpress/checkout/quote"), {
                email: paymentData.email
            });
        },
        placeOrder: function(data, event) {
            var self = this,
                placeOrder;
            console.log(self);
            if (event) {
                event.preventDefault();
            }

            if (this.validate() && additionalValidators.validate()) {
                this.isPlaceOrderActionAllowed(false);
                placeOrder = placeOrderAction(
                    this.getData(),
                    this.redirectAfterPlaceOrder,
                    this.messageContainer
                );

                var promise = $.when(placeOrder)
                    .fail(function() {
                        self.isPlaceOrderActionAllowed(true);
                    })
                    .done(this.afterPlaceOrder.bind(this));

                promise.then(function() {
                    console.debug("This was resolved immediately");
                });
                return true;
            }

            return false;
        },
        getData: function() {
            console.log(this.item.method);
            return {
                method: this.item.method,
                additional_data: {}
            };
        },
        validate: function() {
            return true;
        }
    });
});
