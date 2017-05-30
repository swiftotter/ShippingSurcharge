<?php
/**
 * SwiftOtter_Base is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * SwiftOtter_Base is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with SwiftOtter_Base. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright: 2013 (c) SwiftOtter Studios
 *
 * @author Tyler Schade
 * @copyright Swift Otter Studios, 11/30/16
 * @package default
 **/

namespace SwiftOtter\ShippingSurcharge;

use SwiftOtter\ShippingSurcharge\Model\Surcharge;
use Magento\TestFramework\ObjectManager;

/**
 * @magentoDbIsolation enabled
 */

class EdgeToEdgeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\Quote\Model\Quote
     */
    private $quote;

    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    private $quoteRepository;

    protected function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->productRepository = $this->objectManager->create(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->quote = $this->objectManager->create(\Magento\Quote\Model\Quote::class);
        $this->quoteRepository = $this->objectManager->create(\Magento\Quote\Model\QuoteRepository::class);
    }

    /**
     * @magentoDataFixture Magento/Checkout/_files/active_quote.php
     */
    public function testSurchargeAppliesToOneProduct()
    {
        $surchargeAmount = '14.02';

        $product = $this->getMockProduct(1, $surchargeAmount);
        $quote = $this->getMockQuote($product);
        $totals = $quote->getTotals();

        $this->assertEquals((float) $product->getPrice(), $totals['subtotal']->getValue());
        $this->assertEquals((float) $surchargeAmount, $product->getData(Surcharge::SURCHARGE));
        $this->assertEquals((float) $surchargeAmount, $totals[Surcharge::SURCHARGE]->getValue());

        $this->assertEquals((float) $product->getPrice() + (float) $surchargeAmount, $quote->getGrandTotal());
    }

    /**
     * @magentoDataFixture Magento/Checkout/_files/active_quote.php
     */
    public function testSurchargeAppliesToMultipleProducts()
    {
        $surchargeAmounts = [20.04, 0, 4.84];

        $products = array_map(function($surcharge, $key) {
            return $this->getMockProduct($key, $surcharge);
        }, $surchargeAmounts, array_keys($surchargeAmounts));

        $quote = $this->getMockQuote($products);
        $totals = $quote->getTotals();

        $this->assertEquals(array_sum($surchargeAmounts), $totals[Surcharge::SURCHARGE]->getValue());
        $this->assertEquals(((count($surchargeAmounts) * 10) + array_sum($surchargeAmounts)), $quote->getGrandTotal());
    }

    /**
     * @magentoDataFixture Magento/Checkout/_files/quote_with_address_saved.php
     */
    public function testSurchargeAppliesThroughOrder()
    {
        $quoteManagement = $this->objectManager->create(\Magento\Quote\Model\QuoteManagement::class);

        $surchargeAmounts = [21.19, 1, 12.82];

        $products = array_map(function($surcharge, $key) {
            return $this->getMockProduct($key, $surcharge);
        }, $surchargeAmounts, array_keys($surchargeAmounts));

        $quote = $this->getMockQuote($products);

        $addressData = include __DIR__ . '/../../../../../dev/tests/integration/testsuite/Magento/Sales/_files/address_data.php';
        /** @var \Magento\Quote\Model\Quote\Address $billingAddress */
        $billingAddress = $this->objectManager->create(\Magento\Quote\Model\Quote\Address::class, ['data' => $addressData]);
        $billingAddress->setAddressType('billing');

        $shippingAddress = clone $billingAddress;
        $shippingAddress->setId(null)->setAddressType('shipping');

        $quote->setBillingAddress($billingAddress);
        $quote->setShippingAddress($shippingAddress);
        $quote->getPayment()->setMethod('checkmo');
        $quote->setCustomerIsGuest(true);
        $quote->getShippingAddress()->setShippingMethod('flatrate_flatrate');
        $quote->getShippingAddress()->setCollectShippingRates(true);
        $quote->getShippingAddress()->collectShippingRates();
        $quote->collectTotals();

        /** @var \Magento\Sales\Api\Data\OrderInterface $order */
        $order = $quoteManagement->submit($quote);

        /** # of products * Product Price + sum of surchages + product price already in quote */
        $this->assertEquals(((count($surchargeAmounts) * 10) + array_sum($surchargeAmounts)) + 20, $order->getGrandTotal());
        $this->assertEquals(array_sum($surchargeAmounts), $order->getData(Surcharge::BASE_SURCHARGE));
    }

    private function getMockProduct($id, $surchargeAmount)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->objectManager->create(\Magento\Catalog\Model\Product::class);
        $product->setTypeId(\Magento\Catalog\Model\Product\Type::TYPE_SIMPLE)
            ->setAttributeSetId(4)
            ->setWebsiteIds([1])
            ->setName("Simple Product {$id}")
            ->setSku("simple{$id}")
            ->setPrice(10)
            ->setData(Surcharge::SURCHARGE, $surchargeAmount)
            ->setDescription('Description with <b>html tag</b>')
            ->setVisibility(\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH)
            ->setStatus(\Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED)
            ->setCategoryIds([2])
            ->setStockData(['use_config_manage_stock' => 1, 'qty' => 100, 'is_qty_decimal' => 0, 'is_in_stock' => 1])
            ->setUrlKey("url-key{$id}")
            ->save();

        $this->productRepository->save($product);

        return $product;
    }

    /**
     * @param $product
     * @return \Magento\Quote\Model\Quote
     */
    private function getMockQuote($product)
    {
        /** @var \Magento\Quote\Model\Quote\Address $quoteShippingAddress */
        $quoteShippingAddress = $this->objectManager->create(\Magento\Quote\Model\Quote\Address::class);;

        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $this->quote->load('test_order_1', 'reserved_order_id');

        $quote->setShippingAddress($quoteShippingAddress);

        if (is_array($product)) {
            array_walk($product, function ($item) use (&$quote) {
                $quote->addProduct($item);
            });
        } else {
            $quote->addProduct($product);
        }

        $quote->collectTotals();

        $this->quoteRepository->save($quote);

        return $quote;
    }
}