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
    InstallDataInterface,
    ModuleContextInterface
};
use Magento\Eav\Setup\{
    EavSetupFactory,
    EavSetup
};

use Magento\Sales\Setup\{
    SalesSetup,
    SalesSetupFactory
};

class InstallData implements InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var \Magento\Sales\Setup\SalesSetupFactory
     */
    private $salesSetupFactory;

    public function __construct(EavSetupFactory $eavSetupFactory, SalesSetupFactory $salesSetupFactory)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        $salesSetup->addAttribute('order', 'base_shipping_surcharge', [ 'type' => 'decimal' ]);
        $salesSetup->addAttribute('order', 'shipping_surcharge', [ 'type' => 'decimal' ]);
        $salesSetup->addAttribute('order_item', 'base_shipping_surcharge', [ 'type' => 'decimal' ]);
        $salesSetup->addAttribute('order_item', 'shipping_surcharge', [ 'type' => 'decimal' ]);


        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'shipping_surcharge',
            [
                'type' => 'decimal',
                'label' => 'Shipping Surcharge',
                'required' => false,
                'is_user_defined' => false
            ]
        );
    }
}
