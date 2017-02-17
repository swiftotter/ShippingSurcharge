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
    private $priceCurrency;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
        )
    {
        $this->priceCurrency = $priceCurrency;
        parent::__construct($context, $data);
    }

    protected function formatSurcharge(float $amount): string
    {
        return $this->priceCurrency->convertAndFormat($amount, false);
    }

    public function getSurchargeLabel(): string
    {
        return __($this->surchargeLabel);
    }
}
