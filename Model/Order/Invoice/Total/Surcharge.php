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
        $invoice->setData(SurchargeModel::SURCHARGE, 0);
        $invoice->setData(SurchargeModel::BASE_SURCHARGE, 0);

        $orderSurchargeAmount = $invoice->getOrder()->getData(SurchargeModel::SURCHARGE);
        $baseOrderSurchargeAmount = $invoice->getOrder()->getData(SurchargeModel::BASE_SURCHARGE);

        if ($orderSurchargeAmount) {
            foreach ($invoice->getOrder()->getInvoiceCollection() as $previousInvoice) {
                if ((double)$previousInvoice->getData(SurchargeModel::SURCHARGE) && !$previousInvoice->isCanceled()) {
                    return $this;
                }
            }
            $invoice->setData(SurchargeModel::SURCHARGE, $orderSurchargeAmount);
            $invoice->setData(SurchargeModel::BASE_SURCHARGE, $baseOrderSurchargeAmount);

            $invoice->setGrandTotal($invoice->getGrandTotal() + $orderSurchargeAmount);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseOrderSurchargeAmount);
        }
        return $this;
    }
}