<?php
/**
 * @by SwiftOtter, Inc. 4/19/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Adminhtml\Order;


class Surcharge extends \Magento\Sales\Block\Adminhtml\Order\Totals
{
    use \SwiftOtter\ShippingSurcharge\Block\SurchargeTotal;

    private $configInfo;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        \SwiftOtter\ShippingSurcharge\Config\Info $configInfo,
        array $data = []
    ) {
        $this->configInfo = $configInfo;
        parent::__construct($context, $registry, $adminHelper, $data);
    }
}