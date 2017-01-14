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

namespace SwiftOtter\ShippingSurcharge\Setup;

use Magento\Framework\Setup\{
    ModuleDataSetupInterface,
    ModuleContextInterface,
    UpgradeDataInterface
};
use Magento\Sales\Setup\{
    SalesSetup,
    SalesSetupFactory
};

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var \Magento\Sales\Setup\SalesSetupFactory
     */
    private $salesSetupFactory;

    public function __construct(SalesSetupFactory $salesSetupFactory)
    {
        $this->salesSetupFactory = $salesSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        $salesSetup->addAttribute('order', 'base_shipping_surcharge', [ 'type' => 'decimal' ]);
        $salesSetup->addAttribute('order', 'shipping_surcharge', [ 'type' => 'decimal' ]);
        $salesSetup->addAttribute('order_item', 'base_shipping_surcharge', [ 'type' => 'decimal' ]);
        $salesSetup->addAttribute('order_item', 'shipping_surcharge', [ 'type' => 'decimal' ]);
    }
}
