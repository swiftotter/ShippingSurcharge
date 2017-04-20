/**
 * @by SwiftOtter, Inc. 3/30/17
 * @website https://swiftotter.com
 **/
define([
    'jquery',
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/totals'
], function($, Component, quote, totals) {
    return Component.extend({
        quote: quote,
        totals: quote.getTotals(),

        formattedSurchargeValue: function () {
            return this.getFormattedPrice(this.getSurchargeValue());
        },

        hasSurcharge: function () {
            return !!this.getSurchargeValue();
        },

        getSurchargeValue: function() {
            var surcharge = totals.getSegment('shipping_surcharge');

            return (surcharge && surcharge.value) ? surcharge.value : 0;
        }
    });
});