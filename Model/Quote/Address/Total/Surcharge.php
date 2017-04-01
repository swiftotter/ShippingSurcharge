<?php
/**
 * @by SwiftOtter, Inc. 3/30/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Quote\Address\Total;


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
    private $totalSegmentExtensionFactory;


    public function __construct(
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \SwiftOtter\ShippingSurcharge\Api\Calculator\SurchargeCalculatorInterface $surchargeCalculator,
        \Magento\Quote\Api\Data\ShippingAssignmentExtensionFactory $shippingAssignmentExtensionFactory
    )
    {
        $this->setCode('surcharge');
        $this->priceCurrency = $priceCurrency;
        $this->surchargeCalculator = $surchargeCalculator;
        $this->totalSegmentExtensionFactory = $shippingAssignmentExtensionFactory;
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

            $total->setData('surcharge_amount', $surchargeAmount);
            $total->setTotalAmount($this->getCode(), $this->priceCurrency->convert($surchargeAmount, $store));
            $total->setBaseTotalAmount($this->getCode(), $surchargeAmount);

            $attributes = $this->totalSegmentExtensionFactory->create();
            $attributes->setData('surcharge_amount', $surchargeAmount);
            $shippingAssignment->setExtensionAttributes($attributes);
        }


        return $this;
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        return [
            'code' => $this->getCode(),
            'title' => $this->getLabel(),
            'value' => $total->getData('surcharge_amount')
        ];
    }

    public function getLabel()
    {
        return __('Shipping Surcharge');
    }
}