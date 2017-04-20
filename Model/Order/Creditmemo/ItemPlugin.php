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

        if ($orderItem->getData(SurchargeModel::SURCHARGE) && $item->getQty() > 0) {
            $item->setData(SurchargeModel::SURCHARGE, $orderItem->getData(SurchargeModel::SURCHARGE));
            $item->setData(SurchargeModel::BASE_SURCHARGE, $orderItem->getData(SurchargeModel::BASE_SURCHARGE));
        }
    }
}