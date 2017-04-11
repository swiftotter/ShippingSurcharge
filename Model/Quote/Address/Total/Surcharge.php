<?php
/**
 * @by SwiftOtter, Inc. 3/30/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Quote\Address\Total;

use \SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class Surcharge extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    /**
     * @var \SwiftOtter\ShippingSurcharge\Api\Calculator\SurchargeCalculatorInterface
     */
    private $surchargeCalculator;

    /**
     * @var \Magento\Quote\Api\Data\TotalSegmentExtensionFactory
     */
    private $cartExtensionFactory;


    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \SwiftOtter\ShippingSurcharge\Api\Calculator\SurchargeCalculatorInterface $surchargeCalculator,
        \Magento\Quote\Api\Data\CartExtensionFactory $cartExtensionFactory
    )
    {
        $this->setCode('surcharge');
        $this->priceCurrency = $priceCurrency;
        $this->surchargeCalculator = $surchargeCalculator;
        $this->cartExtensionFactory = $cartExtensionFactory;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $surchargeAmount = $this->surchargeCalculator->calculateSurchargeForItems(...$quote->getAllItems());

        if ($surchargeAmount) {
            $store = $quote->getStore();

            $total->setData(SurchargeModel::SURCHARGE, $surchargeAmount);
            $total->setTotalAmount($this->getCode(), $this->priceCurrency->convert($surchargeAmount, $store));
            $total->setBaseTotalAmount($this->getCode(), $surchargeAmount);
        }


        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $surchargeAmount = $total->getData(SurchargeModel::SURCHARGE) ?? $quote->getData(SurchargeModel::SURCHARGE);

        return [
            'code' => $this->getCode(),
            'title' => $this->getLabel(),
            'value' => $surchargeAmount
        ];
    }

    public function getLabel()
    {
        return __('Shipping Surcharge');
    }
}