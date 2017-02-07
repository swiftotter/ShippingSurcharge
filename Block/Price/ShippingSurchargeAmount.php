<?php
/**
 * @by SwiftOtter, Inc., 2/2/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\Price;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;

class ShippingSurchargeAmount extends Template implements ShippingSurchargeAmountInterface
{
    /** @var \Magento\Catalog\Model\Product */
    private $product;

    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->productRepository = $productRepository;
        $this->product = $this->loadProduct();
    }

    public function getSurcharge(): string
    {
        $surcharge = $this->product->getData('shipping_surcharge');

        if ($surcharge) {
            $surcharge = $this->formatSurcharge((float)$surcharge);
        }

        return (string) $surcharge;
    }

    public function getSurchargeLabel(): string
    {
        return __('Handling: ');
    }

    private function formatSurcharge(float $amount): string
    {
        return sprintf('%.2f', $amount);
    }

    private function loadProduct(): Product
    {
        return $this->productRepository->getById($this->getProductIdFromRequest());
    }

    private function getProductIdFromRequest(): int
    {
        return $this->getRequest()->getParam('id');
    }
}