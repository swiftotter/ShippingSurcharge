<?php
/**
 * @by SwiftOtter, Inc. 4/19/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Order;


class Surcharge extends \Magento\Sales\Block\Order\Totals
{
    use \SwiftOtter\ShippingSurcharge\Block\SurchargeTotal;

    private $configInfo;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \SwiftOtter\ShippingSurcharge\Config\Info $configInfo,
        array $data = []
    ) {
        parent::__construct($context, $registry, $data);
        $this->configInfo = $configInfo;
    }
}