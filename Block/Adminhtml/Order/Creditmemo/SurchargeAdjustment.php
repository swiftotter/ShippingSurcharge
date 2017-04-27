<?php
/**
 * @by SwiftOtter, Inc. 4/19/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Adminhtml\Order\Creditmemo;

use SwiftOtter\ShippingSurcharge\Model\Surcharge as SurchargeModel;

class SurchargeAdjustment extends \Magento\Sales\Block\Adminhtml\Order\Creditmemo\Totals
{
    private $configInfo;
    /**
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    private $priceCurrency;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Helper\Admin $adminHelper,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \SwiftOtter\ShippingSurcharge\Config\Info $configInfo,
        array $data = []
    ) {
        parent::__construct($context, $registry, $adminHelper, $data);
        $this->configInfo = $configInfo;
        $this->priceCurrency = $priceCurrency;
    }

    public function initTotals()
    {
        /** @var $parent \Magento\Sales\Block\Adminhtml\Order\Creditmemo\Totals */
        $parent = $this->getParentBlock();

        $total = new \Magento\Framework\DataObject([
            'code' => 'shipping_surcharge_adjustment',
            'block_name' => $this->getNameInLayout()
        ]);

        $parent->addTotal($total);
        return $this;
    }

    public function showSurcharge()
    {
        return ($this->configInfo->isFeatureEnabled() && $this->getSource());
    }

    public function getSurchargeAmount()
    {
        return $this->getSource()->getData(SurchargeModel::SURCHARGE);
    }

    public function getFormattedSurchargeAmount()
    {
        return $this->priceCurrency->format($this->getSource()->getData(SurchargeModel::SURCHARGE));
    }
}