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
 * @copyright Swift Otter Studios, 12/1/16
 * @package default
 **/

namespace SwiftOtter\ShippingSurcharge\Calculator;

use Magento\Framework\Model\AbstractExtensibleModel;
use Magento\Quote\Model\Quote\Address\RateResult\AbstractResult;
use Magento\Shipping\Model\Rate;
use SwiftOtter\ShippingSurcharge\Api\Calculator\{
    RateCalculatorInterface,
    SurchargeCalculatorInterface
};

class RateCalculator implements RateCalculatorInterface
{
    private $surchargeCalculator;

    public function __construct(
        SurchargeCalculatorInterface $surchargeCalculator
    ) {
        $this->surchargeCalculator = $surchargeCalculator;
    }

    /**
     * @param Rate\Result $rateRequestResults
     * @param AbstractExtensibleModel[] ...$requestItems
     * @return Rate\Result
     */
    public function calculateRates(Rate\Result $rateRequestResults, AbstractExtensibleModel ...$requestItems) : Rate\Result
    {
        $totalSurcharge = $this->surchargeCalculator->calculateSurchargeForItems(...$requestItems);
        $ratesCopy = $rateRequestResults->getAllRates();

        $rateRequestResults->reset();

        array_walk($ratesCopy, function (AbstractResult $rate) use ($rateRequestResults, $totalSurcharge) {
//            $rate->setData('price', $rate->getData('price') + $totalSurcharge);
//            $rate->setData('cost', $rate->getData('cost') + $totalSurcharge);

            $rate->setData('price', $rate->getData('price'));
            $rate->setData('cost', $rate->getData('cost'));
            $rate->setData('total', $rate->getData('price') + $totalSurcharge);
            $rate->setData('added_surcharge', $totalSurcharge);

            $rateRequestResults->append($rate);
        });

        return $rateRequestResults;
    }
}