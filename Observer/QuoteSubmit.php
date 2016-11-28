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
 * @copyright Swift Otter Studios, 11/28/16
 * @package default
 **/

namespace SwiftOtter\ShippingSurcharge\Observer;

use Magento\Framework\Event\ObserverInterface;

class QuoteSubmit implements ObserverInterface
{
    private $productRepository;
    private $orderRepository;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getData('quote');
        $order = $observer->getData('order');

        $this->calculateQuoteTotals($quote);
        $this->calculateOrderTotals($order);
    }

    private function calculateTotalsFromItems(array $items, bool $setOnItems) : float
    {
        return array_reduce($items, function ($carry, $item) use ($setOnItems) {
            $itemTotal = $this->getShippingSurcharge($item->getData('product_id'));

            if ($setOnItems) {
                $item->setData('base_shipping_surcharge', $itemTotal);
                $item->setData('shipping_surcharge', $itemTotal);
            }

            return $carry + $itemTotal;
        }, 0);
    }

    private function calculateQuoteTotals(\Magento\Quote\Model\Quote $quote)
    {
        $shippingSurcharge = $this->calculateTotalsFromItems($quote->getAllItems(), false);

        $quote->setData('shipping_surcharge', $shippingSurcharge);
    }

    private function calculateOrderTotals(\Magento\Sales\Model\Order $order)
    {
        $shippingSurcharge = $this->calculateTotalsFromItems($order->getAllItems(), true);

        $order->setData('base_shipping_surcharge', $shippingSurcharge);
        $order->setData('shipping_surcharge', $shippingSurcharge);

        $this->orderRepository->save($order);
    }

    private function getShippingSurcharge(int $productId) : float
    {
        return (float) $this->productRepository->getById($productId)->getData('shipping_surcharge');
    }
}