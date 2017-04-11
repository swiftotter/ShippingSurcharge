<?php
/**
 * @by SwiftOtter, Inc. 4/11/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Adminhtml\Order;

use \SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class Surcharge extends \Magento\Backend\Block\Template
{
    /**
     * @var \SwiftOtter\ShippingSurcharge\Config\Info
     */
    private $configInfo;

    /**
     * @var \Magento\Framework\DataObject
     */
    protected $source;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \SwiftOtter\ShippingSurcharge\Config\Info $configInfo,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->configInfo = $configInfo;
    }

    public function isShippingSurchargeEnabled() : bool
    {
        return $this->configInfo->isFeatureEnabled();
    }

    public function initTotals()
    {
        /** @var $parent \Magento\Sales\Block\Adminhtml\Order\Invoice\Totals */
        $parent = $this->getParentBlock();
        $this->source = $parent->getOrder();

        $this->initSurcharge();
        return $this;
    }

    /**
     * @return \Magento\Framework\DataObject
     */
    public function getSource(): \Magento\Framework\DataObject
    {
        return $this->source;
    }

    private function initSurcharge()
    {
        /** @var $parent \Magento\Sales\Block\Adminhtml\Order\Invoice\Totals */
        $parent = $this->getParentBlock();

        $surcharge = new \Magento\Framework\DataObject(
            [
                'code' => SurchargeModel::SURCHARGE,
                'value' => $this->getSource()->getData(SurchargeModel::SURCHARGE),
                'base_value' => $this->getSource()->getData(SurchargeModel::BASE_SURCHARGE),
                'label' => __('Additional Shipping Charge'),
            ]
        );

        $parent->addTotal($surcharge, 'shipping');
    }
}