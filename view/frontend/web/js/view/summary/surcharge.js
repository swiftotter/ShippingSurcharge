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
            console.log(surcharge);
            console.log('Looking for surcharge');

            console.log(this.totals());
            console.log(this.quote);

            if (surcharge && surcharge.value) {
                return surcharge.value;
            }

            console.log('No surcharge found');

            return 0;
        }
    });
});