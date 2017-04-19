<?php
/**
 * @by SwiftOtter, Inc. 4/19/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Adminhtml\Order\Creditmemo;

class Surcharge extends \Magento\Sales\Block\Adminhtml\Order\Creditmemo\Totals
{
    use \SwiftOtter\ShippingSurcharge\Block\SurchargeTotal;

    private $configInfo;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        \SwiftOtter\ShippingSurcharge\Config\Info $configInfo,
        array $data = [])
    {
        parent::__construct($context, $registry, $adminHelper, $data);
        $this->configInfo = $configInfo;
    }

    public function initTotals()
    {
        $this->initSurcharge();
        return $this;
    }
}