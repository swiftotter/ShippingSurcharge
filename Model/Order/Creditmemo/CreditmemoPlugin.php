<?php
/**
 * @by SwiftOtter, Inc. 4/27/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Order\Creditmemo;

use SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class CreditmemoPlugin
{
    public function beforeCreateByInvoice(\Magento\Sales\Model\Order\CreditmemoFactory $context, \Magento\Sales\Model\Order\Invoice $invoice, array $data)
    {
        if (isset($data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]) && $data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]) {
            $invoice->setData(SurchargeModel::SURCHARGE_REQUESTED_REFUND, $data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]);
        }

        return [$invoice, $data];
    }

    public function beforeCreateByOrder(\Magento\Sales\Model\Order\CreditmemoFactory $context, \Magento\Sales\Model\Order $order, array $data = [])
    {
        if (isset($data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]) && $data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]) {
            $order->setData(SurchargeModel::SURCHARGE_REQUESTED_REFUND, $data[SurchargeModel::SURCHARGE_REQUESTED_REFUND]);
        }

        return [$order, $data];
    }
}