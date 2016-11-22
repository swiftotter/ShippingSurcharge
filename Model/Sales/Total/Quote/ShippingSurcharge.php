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
 * @copyright Swift Otter Studios, 11/15/16
 * @package default
 **/

namespace SwiftOtter\ShippingSurcharge\Model\Sales\Total\Quote;

class ShippingSurcharge extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->setCode('shipping_surcharge');
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    )
    {
        parent::collect($quote, $shippingAssignment, $total);

        $total->setTotalAmount($this->getCode(), 0);
        $total->setBaseTotalAmount($this->getCode(), 0);

        if ($this->scopeConfig->getValue('shipping_surcharge/general/is_enabled')) {
            $totalSurcharge = array_reduce($quote->getAllItems(), function ($carry, $item) {
                /** @var \Magento\Quote\Model\Quote\Item */
                return $carry + $item->getData('product')->getData('shipping_surcharge');
            }, 0);

            $total->setTotalAmount($this->getCode(), $totalSurcharge);
            $total->setBaseTotalAmount($this->getCode(), $totalSurcharge);

            $this->total->setTotalAmount('shipping', $this->total->getTotalAmount('shipping') + $totalSurcharge);
            $this->total->setBaseTotalAmount('shipping', $this->total->getBaseTotalAmount('shipping') + $totalSurcharge);
        }

        return $this;
    }

    public function getLabel()
    {
        return __('Shipping sur-charge');
    }
}