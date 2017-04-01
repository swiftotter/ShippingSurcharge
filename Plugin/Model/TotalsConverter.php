<?php
/**
 * @by SwiftOtter, Inc. 3/30/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Plugin\Model;


class TotalsConverter
{
    /**
     * @var \SwiftOtter\ShippingSurcharge\Api\Data\SurchargeSegmentExtension
     */
    private $totalSegmentExtensionFactory;

    public function __construct(\SwiftOtter\ShippingSurcharge\Api\Data\SurchargeSegmentExtensionFactory $totalSegmentExtensionFactory)
    {
        $this->totalSegmentExtensionFactory = $totalSegmentExtensionFactory;
    }

    public function afterProcess(\Magento\Quote\Model\Cart\TotalsConverter $context, $data)
    {
        if (isset($data['surcharge'])) {
//            $attributes = $this->totalSegmentExtensionFactory->create();
//            $attributes->setSurchargeAmount($data['surcharge']->getData('value'));
//            $data['surcharge']->setExtensionAttributes($attributes);
        }

        return $data;
    }
}