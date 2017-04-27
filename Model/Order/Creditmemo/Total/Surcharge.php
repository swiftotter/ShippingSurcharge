<?php
/**
 * @by SwiftOtter, Inc. 4/11/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Order\Creditmemo\Total;

use SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class Surcharge extends \Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    public function __construct(\Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency, array $data = [])
    {
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

        list($allowedSurcharged, $baseAllowedSurcharged) = $this->getAllowedAmounts($order);
        $desiredAmount = (float) $order->getData(SurchargeModel::SURCHARGE_REQUESTED_REFUND);

        if ($desiredAmount && $desiredAmount <= $allowedSurcharged) {
            $surcharge = $desiredAmount;
            $baseSurcharge = $desiredAmount;
        } else {
            list($surcharge, $baseSurcharge) = $this->calculateSurchargeAmounts($creditmemo);
        }

        if ($surcharge > $allowedSurcharged) {
            $surcharge = $allowedSurcharged;
            $baseSurcharge = $baseAllowedSurcharged;
        }

        $surcharge = $this->priceCurrency->round($surcharge);
        $baseSurcharge = $this->priceCurrency->round($baseSurcharge);

        $this->setCreditmemoData($creditmemo, $surcharge, $baseSurcharge);
        $this->setOrderData($order, $surcharge, $baseSurcharge);

        return $this;
    }

    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @param $surcharge
     * @param $baseSurcharge
     */
    public function setCreditmemoData(\Magento\Sales\Model\Order\Creditmemo $creditmemo, $surcharge, $baseSurcharge)
    {
        $creditmemo->setData(SurchargeModel::SURCHARGE, $surcharge);
        $creditmemo->setData(SurchargeModel::BASE_SURCHARGE, $baseSurcharge);
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $surcharge);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseSurcharge);
    }

    /**
     * @param $order
     * @param $surcharge
     * @param $baseSurcharge
     */
    public function setOrderData(\Magento\Sales\Model\Order $order, $surcharge, $baseSurcharge)
    {
        $order->setData(SurchargeModel::SURCHARGE_REFUNDED, ($order->getData(SurchargeModel::SURCHARGE_REFUNDED) + $surcharge));
        $order->setData(SurchargeModel::BASE_SURCHARGE_REFUNDED, ($order->getData(SurchargeModel::BASE_SURCHARGE_REFUNDED) + $baseSurcharge));
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    private function getAllowedAmounts(\Magento\Sales\Model\Order $order): array
    {
        $allowedSurcharged = $order->getData(SurchargeModel::SURCHARGE) - $order->getData(SurchargeModel::SURCHARGE_REFUNDED);
        $baseAllowedSurcharged = $order->getData(SurchargeModel::BASE_SURCHARGE) - $order->getData(SurchargeModel::BASE_SURCHARGE_REFUNDED);
        return [$allowedSurcharged, $baseAllowedSurcharged];
    }

    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return array
     */
    private function calculateSurchargeAmounts(\Magento\Sales\Model\Order\Creditmemo $creditmemo): array
    {
        $surcharge = 0;
        $baseSurcharge = 0;

        foreach ($creditmemo->getAllItems() as $item) {
            if ($item->getOrderItem()->isDummy()) {
                continue;
            }

            $item->calcRowTotal();

            $surcharge += $item->getData(SurchargeModel::SURCHARGE);
            $baseSurcharge += $item->getData(SurchargeModel::BASE_SURCHARGE);
        }

        return [$surcharge, $baseSurcharge];
    }
}