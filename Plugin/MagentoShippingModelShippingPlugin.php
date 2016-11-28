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
 * @copyright Swift Otter Studios, 11/25/16
 * @package default
 **/

namespace SwiftOtter\ShippingSurcharge\Plugin;

class MagentoShippingModelShippingPlugin
{
    private $configHelper;
    private $productRepository;

    public function __construct(
        \SwiftOtter\ShippingSurcharge\Helper\Config $configHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    )
    {
        $this->configHelper = $configHelper;
        $this->productRepository = $productRepository;
    }

    public function aroundCollectRates(
        \Magento\Shipping\Model\Shipping $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Address\RateRequest $request
    )
    {
        if ($this->configHelper->isEnabled()) {
            $totalSurcharge = $this->getTotalSurcharge($request->getData('all_items'));
            $shipping = $proceed($request);
            /** @var \Magento\Shipping\Model\Rate\Result $results */
            $results = clone $shipping->getResult();
            $rates = $results->getAllRates();

            $shipping->getResult()->reset();

            array_walk($rates, function ($rate) use ($shipping, $totalSurcharge) {
                $rate->setData('price', $rate->getData('price') + $totalSurcharge);
                $shipping->getResult()->append($rate);
            });

            return $shipping;
        } else {
            return $proceed();
        }
    }

    private function getTotalSurcharge(array $items) : float
    {
        return (float) array_reduce($items, function ($carry, \Magento\Quote\Model\Quote\Item $item) {
            $product = $this->productRepository->getById($item->getData('product_id'));

            return $carry += ($product->getData('shipping_surcharge') * $item->getQty());
        }, 0);
    }
}