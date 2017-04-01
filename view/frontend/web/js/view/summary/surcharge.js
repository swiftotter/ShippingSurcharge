/**
 * @by SwiftOtter, Inc. 3/30/17
 * @website https://swiftotter.com
 **/
define([
    'jquery',
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote'
], function($, Component, quote) {
    return Component.extend({
        quote: quote,
        totals: quote.getTotals(),

        hasSurcharge: function () {
            return true
        },

        getSurchargeValue: function() {
            console.log(this.totals());
            console.log('Amount: ' + this.totals().surcharge_amount);
            return this.totals().surcharge_amount || 'N/A';
        }
    });
});