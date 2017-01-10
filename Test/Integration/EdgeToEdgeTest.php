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

/**
 * @magentoDbIsolation enabled
 */

class EdgeToEdgeTest extends \PHPUnit_Framework_Testcase
{
    public function testNothing()
    {
        $this->fail('asdfasdf');
    }

    public function createTableRateFixture($amount)
    {
        //Create table rate fixture
    }

    /**
     * @magentoDataFixture Magento/Quote/_files/empty_quote.php
     * @magentoDataFixture Magento/Catalog/_files/product_simple.php
     */
    public function testAppliesSurchargeToTablerate()
    {
        //Load the product, set a surcharge, and save. Add product to quote, save the quote. Get shipping instance, collect shipping rates. Ensure table rate fixture plus the surcharge works.
    }
}