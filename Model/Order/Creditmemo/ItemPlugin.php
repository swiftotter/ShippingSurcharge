<?php
/**
 * @by SwiftOtter, Inc. 4/19/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Order\Creditmemo;

use SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class ItemPlugin
{
    public function afterCalcRowTotal(\Magento\Sales\Model\Order\Creditmemo\Item $item)
    {
        $orderItem = $item->getOrderItem();
        $itemQty = $item->getQty();

        if ($orderItem->getData(SurchargeModel::SURCHARGE) && $itemQty > 0) {
            $item->setData(SurchargeModel::SURCHARGE, $this->calculateSurchargeFrom($orderItem, $itemQty));
            $item->setData(SurchargeModel::BASE_SURCHARGE, $this->calculateSurchargeFrom($orderItem, $itemQty, SurchargeModel::BASE_SURCHARGE));
        }
    }

    private function calculateSurchargeFrom(\Magento\Sales\Model\Order\Item $orderItem, $itemQty, $key = SurchargeModel::SURCHARGE)
    {
        return (int) ceil(($orderItem->getData($key) / $orderItem->getQtyOrdered()) * $itemQty);
    }
}