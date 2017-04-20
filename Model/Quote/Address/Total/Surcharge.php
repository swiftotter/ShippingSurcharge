<?php
/**
 * @by SwiftOtter, Inc. 3/30/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Quote\Address\Total;

use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
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
    ) {
        $this->setCode(SurchargeModel::SURCHARGE);

        $this->priceCurrency = $priceCurrency;
        $this->surchargeCalculator = $surchargeCalculator;
        $this->cartExtensionFactory = $cartExtensionFactory;
    }

    public function collect(Quote $quote, ShippingAssignmentInterface $shippingAssignment, Quote\Address\Total $total) {
        parent::collect($quote, $shippingAssignment, $total);

        if ($surchargeAmount = $this->calculateSurcharge($quote)) {
            $quote->setData(SurchargeModel::SURCHARGE, $surchargeAmount);

            $total->setTotalAmount(SurchargeModel::SURCHARGE, $this->priceCurrency->convert($surchargeAmount, $quote->getStore()));
            $total->setBaseTotalAmount(SurchargeModel::SURCHARGE, $surchargeAmount);
        }


        return $this;
    }

    private function calculateSurcharge(Quote $quote)
    {
        return $this->surchargeCalculator->calculateSurchargeForItems(...$quote->getAllItems());
    }

    public function fetch(Quote $quote, Quote\Address\Total $total)
    {
        return [
            'code' => $this->getCode(),
            'title' => $this->getLabel(),
            'value' => $this->loadSurchargeAmount($quote, $total)
        ];
    }

    private function loadSurchargeAmount(Quote $quote, Quote\Address\Total $total)
    {
        if ($total->getTotalAmount(SurchargeModel::SURCHARGE)) {
            $surcharge = $total->getTotalAmount(SurchargeModel::SURCHARGE);
        } else if ($quote->getData(SurchargeModel::SURCHARGE)) {
            $surcharge = $quote->getData(SurchargeModel::SURCHARGE);
        } else {
            $surcharge = $this->calculateSurcharge($quote);
        }

        return $surcharge;
    }

    public function getLabel()
    {
        return __('Shipping Surcharge');
    }
}