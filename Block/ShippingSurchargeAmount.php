<?php
/**
 * @by SwiftOtter, Inc., 2/10/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block;

use Magento\Framework\View\Element\Template;
use SwiftOtter\ShippingSurcharge\Api\Block\Product\ShippingSurchargeAmountInterface;
use SwiftOtter\ShippingSurcharge\Setup\Definition\ExplanatoryNoteStaticBlock;

abstract class ShippingSurchargeAmount extends Template implements ShippingSurchargeAmountInterface
{
    /**
     * @var string
     */
    protected $surchargeLabel;
    private $priceCurrency;
    private $blockRepository;
    private $filterProvider;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Cms\Api\BlockRepositoryInterface $blockRepository,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        array $data = []
        )
    {
        $this->blockRepository = $blockRepository;
        $this->priceCurrency = $priceCurrency;
        $this->filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    protected function formatSurcharge(float $amount): string
    {
        return $this->priceCurrency->convertAndFormat($amount, false);
    }

    public function getSurchargeNote(): string
    {
        $block = $this->blockRepository->getById(ExplanatoryNoteStaticBlock::ID);
        $storeId = $this->_storeManager->getStore()->getId();
        $filteredContent = $this->filterProvider->getBlockFilter()->setStoreId($storeId)->filter($block->getContent());

        return strip_tags($filteredContent);
    }

    public function getSurchargeLabel(): string
    {
        return __($this->surchargeLabel);
    }
}
