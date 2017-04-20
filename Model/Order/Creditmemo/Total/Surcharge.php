<?php
/**
 * @by SwiftOtter, Inc. 4/11/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Order\Creditmemo\Total;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class Surcharge extends \Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal
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

        $creditmemo->setData(SurchargeModel::SURCHARGE, $surcharge);
        $creditmemo->setData(SurchargeModel::BASE_SURCHARGE, $baseSurcharge);
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $surcharge);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseSurcharge);

        return $this;
    }
}