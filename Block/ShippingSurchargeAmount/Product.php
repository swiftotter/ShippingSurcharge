<?php
/**
 * @by SwiftOtter, Inc., 2/2/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Block\ShippingSurchargeAmount;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product as CatalogProduct;
use SwiftOtter\ShippingSurcharge\Api\Block\Product\ShippingSurchargeAmountInterface;
use SwiftOtter\ShippingSurcharge\Block\ShippingSurchargeAmount;

class Product extends ShippingSurchargeAmount implements ShippingSurchargeAmountInterface
{
    /** @var \Magento\Catalog\Model\Product */
    private $product;

    /** @var \Magento\Catalog\Api\ProductRepositoryInterface */
    private $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        Template\Context $context,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $priceCurrency, $data);

        $this->surchargeLabel = 'Handling';
        $this->productRepository = $productRepository;
        $this->product = $this->loadProduct();
    }

    public function hasSurcharge(): bool
    {
        return (bool) $this->product->getData('shipping_surcharge');
    }

    public function getSurcharge(): string
    {
        $surcharge = $this->product->getData('shipping_surcharge');

        if ($surcharge) {
            $surcharge = $this->formatSurcharge((float)$surcharge);
        }

        return (string) $surcharge;
    }

    private function loadProduct(): CatalogProduct
    {
        return $this->productRepository->getById($this->getProductIdFromRequest());
    }

    private function getProductIdFromRequest(): int
    {
        return $this->getRequest()->getParam('id');
    }
}