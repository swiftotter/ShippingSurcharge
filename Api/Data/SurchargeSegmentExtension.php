<?php
/**
 * @by SwiftOtter, Inc. 3/30/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Api\Data;


class SurchargeSegmentExtension extends \Magento\Framework\Api\AbstractSimpleObject implements \Magento\Framework\Api\ExtensionAttributesInterface
{
    public function getSurchargeAmount()
    {
        return $this->_get('surcharge_amount');
    }

    public function setSurchargeAmount($surchargeAmount)
    {
        $this->setData('surcharge_amount', $surchargeAmount);
        return $this;
    }
}