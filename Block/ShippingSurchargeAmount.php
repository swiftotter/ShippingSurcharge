<?php
/**
 * @by SwiftOtter, Inc., 2/10/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block;

use Magento\Framework\View\Element\Template;
use SwiftOtter\ShippingSurcharge\Api\Block\Product\ShippingSurchargeAmountInterface;

abstract class ShippingSurchargeAmount extends Template implements ShippingSurchargeAmountInterface
{
    /**
     * @var string
     */
    protected $surchargeLabel;

    abstract public function getSurcharge(): string;

    protected function formatSurcharge(float $amount): string
    {
        return sprintf('%.2f', $amount);
    }

    public function getSurchargeLabel(): string
    {
        return __($this->surchargeLabel);
    }
}
