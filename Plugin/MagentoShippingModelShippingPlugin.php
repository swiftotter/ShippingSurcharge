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

use SwiftOtter\ShippingSurcharge\Api\Calculator\RateCalculatorInterface;

class MagentoShippingModelShippingPlugin
{
    private $configInfo;
    private $productRepository;
    private $rateCalculator;

    public function __construct(
        \SwiftOtter\ShippingSurcharge\Config\Info $configHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        RateCalculatorInterface $rateCalculator
    ) {
        $this->rateCalculator = $rateCalculator;
        $this->configInfo = $configHelper;
        $this->productRepository = $productRepository;
    }

    public function aroundCollectRates(
        \Magento\Shipping\Model\Shipping $subject,
        \Closure $proceed,
        \Magento\Quote\Model\Quote\Address\RateRequest $request
    ) {
        if ($this->configInfo->isFeatureEnabled()) {
            $requestItems = $request->getData('all_items');
            /** @var \Magento\Shipping\Model\Shipping $shipping */
            $shipping = $proceed($request);

            $this->rateCalculator->calculateRates($shipping->getResult(), ...$requestItems);

            return $shipping;
        } else {
            return $proceed($request);
        }
    }
}