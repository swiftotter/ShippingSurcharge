<?php
/**
 * @by SwiftOtter, Inc. 4/11/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Order\Invoice;

class Surcharge extends \Magento\Sales\Block\Order\Invoice\Totals
{
    use \SwiftOtter\ShippingSurcharge\Block\SurchargeTotal;

    private $configInfo;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \SwiftOtter\ShippingSurcharge\Config\Info $configInfo,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $data);
        $this->configInfo = $configInfo;
    }

    public function initTotals()
    {
        $this->initSurcharge();
        return $this;
    }
}