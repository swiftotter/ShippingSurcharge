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
        $this->setCode(SurchargeModel::SURCHARGE);
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

        $surchargeAmount = $this->calculateSurcharge($quote);

        if ($surchargeAmount) {
            $store = $quote->getStore();

            $quote->setData(SurchargeModel::SURCHARGE, $surchargeAmount);

            $total->setTotalAmount(SurchargeModel::SURCHARGE, $this->priceCurrency->convert($surchargeAmount, $store));
            $total->setBaseTotalAmount(SurchargeModel::SURCHARGE, $surchargeAmount);
        }


        return $this;
    }

    private function calculateSurcharge(\Magento\Quote\Model\Quote $quote)
    {
        return $this->surchargeCalculator->calculateSurchargeForItems(...$quote->getAllItems());
    }

    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $surchargeAmount = $this->loadSurchargeAmount($quote, $total);

        return [
            'code' => $this->getCode(),
            'title' => $this->getLabel(),
            'value' => $surchargeAmount
        ];
    }

    private function loadSurchargeAmount(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        if ($total->getTotalAmount(SurchargeModel::SURCHARGE)) {
            return $total->getTotalAmount(SurchargeModel::SURCHARGE);
        }

        if ($quote->getData(SurchargeModel::SURCHARGE)) {
            return $quote->getData(SurchargeModel::SURCHARGE);
        }

        return $this->calculateSurcharge($quote);
    }

    public function getLabel()
    {
        return __('Shipping Surcharge');
    }
}