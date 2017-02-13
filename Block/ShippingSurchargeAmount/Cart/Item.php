<?php
/**
 * @by SwiftOtter, Inc., 2/10/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\ShippingSurchargeAmount\Cart;

use SwiftOtter\ShippingSurcharge\Api\Block\Product\ShippingSurchargeAmountInterface;
use SwiftOtter\ShippingSurcharge\Block\ShippingSurchargeAmount;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class Item extends ShippingSurchargeAmount implements ShippingSurchargeAmountInterface
{
    /**
     * @var QuoteItem
     */
    private $quoteItem;

    protected $surchargeLabel = 'Handling';

    public function getSurcharge(): string
    {
        return $this->formatSurcharge($this->quoteItem->getProduct()->getData('shipping_surcharge') * $this->quoteItem->getQty());
    }

    public function setQuoteItem(QuoteItem $quoteItem): Item
    {
        $this->quoteItem = $quoteItem;

        return $this;
    }
}
