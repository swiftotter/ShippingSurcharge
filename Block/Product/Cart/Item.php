<?php
/**
 * @by SwiftOtter, Inc., 2/10/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Product\Cart;

use SwiftOtter\ShippingSurcharge\Api\Block\Product\ShippingSurchargeAmountInterface;
use SwiftOtter\ShippingSurcharge\Block\ItemSurcharge;
use Magento\Quote\Model\Quote\Item as QuoteItem;

class Item extends ItemSurcharge implements ShippingSurchargeAmountInterface
{
    /**
     * @var QuoteItem
     */
    private $quoteItem;

    protected $surchargeLabel = 'Additional Shipping Charge';

    public function hasSurcharge(): bool
    {
        return (bool) $this->quoteItem->getProduct()->getData('shipping_surcharge');
    }

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
