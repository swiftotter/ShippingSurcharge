<?php
/**
 * @by SwiftOtter, Inc. 4/11/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Order\Invoice\Total;

use SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class Surcharge extends \Magento\Sales\Model\Order\Invoice\Total\AbstractTotal
{
    public function collect(\Magento\Sales\Model\Order\Invoice $invoice)
    {
        $surcharge = 0;
        $baseSurcharge = 0;

        /** @var \Magento\Sales\Model\Order\Invoice\Item $item */
        foreach ($invoice->getAllItems() as $item) {
            $orderItem = $item->getOrderItem();
            $orderItemQty = $orderItem->getQtyOrdered();

            if (!$orderItemQty || $orderItem->isDummy() || $item->getQty() < 1) {
                continue;
            }

            $item->setData(SurchargeModel::SURCHARGE, $orderItem->getData(SurchargeModel::SURCHARGE));
            $item->setData(SurchargeModel::BASE_SURCHARGE, $orderItem->getData(SurchargeModel::BASE_SURCHARGE));

            $surcharge += $item->getData(SurchargeModel::SURCHARGE);
            $baseSurcharge += $item->getData(SurchargeModel::BASE_SURCHARGE);
        }

        $invoice->setData(SurchargeModel::SURCHARGE, $surcharge);
        $invoice->setData(SurchargeModel::BASE_SURCHARGE, $baseSurcharge);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $surcharge);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseSurcharge);

        return $this;
    }
}