<?php
/**
 * @by SwiftOtter, Inc. 4/11/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Order\Creditmemo\Total;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class Shipping extends \Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal
{
    private $priceCurrency;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($data);
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo)
    {
        $order = $creditmemo->getOrder();

        $orderSurchargeAmount = $order->getData(SurchargeModel::SURCHARGE);
        $orderBaseSurchargeAmount = $order->getData(SurchargeModel::BASE_SURCHARGE);
        $allowedAmount = $orderSurchargeAmount - $order->getData(SurchargeModel::SURCHARGE_REFUNDED);
        $baseAllowedAmount = $orderBaseSurchargeAmount - $order->getData(SurchargeModel::BASE_SURCHARGE_REFUNDED);

        $surchargeAmount = $baseSurchargeAmount = 0;

        if ($creditmemo->hasData(SurchargeModel::BASE_SURCHARGE)) {
            $desiredAmount = $this->priceCurrency->round($creditmemo->getData(SurchargeModel::BASE_SURCHARGE));
            $maxAllowedAmount = $baseAllowedAmount;
            $originalTotalAmount = $orderBaseSurchargeAmount;

            // Note: ($x < $y + 0.0001) means ($x <= $y) for floats
            if ($desiredAmount < $this->priceCurrency->round($maxAllowedAmount) + 0.0001) {
                // since the admin is returning less than the allowed amount, compute the ratio being returned
                $ratio = 0;
                if ($originalTotalAmount > 0) {
                    $ratio = $desiredAmount / $originalTotalAmount;
                }

                // capture amounts without tax
                // Note: ($x > $y - 0.0001) means ($x >= $y) for floats
                if ($desiredAmount > $maxAllowedAmount - 0.0001) {
                    $surchargeAmount = $allowedAmount;
                    $baseSurchargeAmount = $baseAllowedAmount;
                } else {
                    $surchargeAmount = $this->priceCurrency->round($orderSurchargeAmount * $ratio);
                    $baseSurchargeAmount = $this->priceCurrency->round($orderBaseSurchargeAmount * $ratio);
                }

            } else {
                $maxAllowedAmount = $order->getBaseCurrency()->format($maxAllowedAmount, null, false);
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Maximum shipping amount allowed to refund is: %1', $maxAllowedAmount)
                );
            }
        } else {
            $surchargeAmount = $allowedAmount;
            $baseSurchargeAmount = $baseAllowedAmount;
        }

        $creditmemo->setData(SurchargeModel::SURCHARGE, $surchargeAmount);
        $creditmemo->setData(SurchargeModel::BASE_SURCHARGE, $baseSurchargeAmount);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $surchargeAmount);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseSurchargeAmount);

        return $this;
    }
}