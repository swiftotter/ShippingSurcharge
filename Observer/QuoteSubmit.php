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

use Magento\Framework\Event\{
    ObserverInterface,
    Observer as Event
};
use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;
use SwiftOtter\ShippingSurcharge\Api\Calculator\SurchargeCalculatorInterface;
use SwiftOtter\ShippingSurcharge\Config;
use SwiftOtter\ShippingSurcharge\Model\Surcharge;

class QuoteSubmit implements ObserverInterface
{
    private $productRepository;
    private $orderRepository;
    private $configInfo;
    private $surchargeCalculator;

    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        Config\Info $configInfo,
        SurchargeCalculatorInterface $surchargeCalculator
    ) {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->configInfo = $configInfo;
        $this->surchargeCalculator = $surchargeCalculator;
    }

    public function execute(Event $event)
    {
        if ($this->configInfo->isFeatureEnabled()) {
            $quote = $event->getData('quote');
            $order = $event->getData('order');

            $this->calculateQuoteTotals($quote);
            $this->calculateOrderTotals($order);
        }
    }

    private function calculateTotalsFromItems(AbstractExtensibleModel ...$items) : float
    {
        return array_reduce($items, function (float $acc, AbstractExtensibleModel $item) {
            $itemTotal = $this->surchargeCalculator->calculateSurchargeForItem($item);

            $item->setData(Surcharge::BASE_SURCHARGE, $itemTotal);
            $item->setData(Surcharge::SURCHARGE, $itemTotal);

            return $acc + $itemTotal;
        }, 0);
    }

    private function calculateQuoteTotals(Quote $quote)
    {
        $shippingSurcharge = $this->surchargeCalculator->calculateSurchargeForItems(...$quote->getAllItems());

        $quote->setData(Surcharge::SURCHARGE, $shippingSurcharge);
    }

    private function calculateOrderTotals(Order $order)
    {
        $shippingSurcharge = $this->calculateTotalsFromItems(...$order->getAllItems());

        $order->setData(Surcharge::BASE_SURCHARGE, $shippingSurcharge);
        $order->setData(Surcharge::SURCHARGE, $shippingSurcharge);

        $this->orderRepository->save($order);
    }
}