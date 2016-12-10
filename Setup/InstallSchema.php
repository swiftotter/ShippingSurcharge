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
 * @copyright Swift Otter Studios, 11/21/16
 * @package default
 **/

namespace SwiftOtter\ShippingSurcharge\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Sales\Setup\SalesSetup as SalesSetupResource;

class InstallSchema implements InstallSchemaInterface
{
    private $salesSetupResource;

    public function __construct(SalesSetupResource $salesSetupResource)
    {
        $this->salesSetupResource = $salesSetupResource;
    }

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
//        $setup->getConnection()->addColumn(
//            $setup->getTable('sales_order'),
//            'base_shipping_surcharge',
//            [
//                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
//                'nullable' => true,
//                'comment' => 'Base shipping surcharge'
//            ]
//        );
//
//        $setup->getConnection()->addColumn(
//            $setup->getTable('sales_order'),
//            'shipping_surcharge',
//            [
//                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
//                'nullable' => true,
//                'comment' => 'Shipping surcharge'
//            ]
//        );
//
//        $setup->getConnection()->addColumn(
//            $setup->getTable('sales_order_item'),
//            'base_shipping_surcharge',
//            [
//                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
//                'nullable' => true,
//                'comment' => 'Base shipping surcharge'
//            ]
//        );
//
//        $setup->getConnection()->addColumn(
//            $setup->getTable('sales_order_item'),
//            'shipping_surcharge',
//            [
//                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
//                'nullable' => true,
//                'comment' => 'Shipping surcharge'
//            ]
//        );

        $this->salesSetupResource->addAttribute('order', 'base_shipping_surcharge', [ 'type' => 'decimal' ]);
        $this->salesSetupResource->addAttribute('order', 'shipping_surcharge', [ 'type' => 'decimal' ]);
        $this->salesSetupResource->addAttribute('order_item', 'base_shipping_surcharge', [ 'type' => 'decimal' ]);
        $this->salesSetupResource->addAttribute('order_item', 'shipping_surcharge', [ 'type' => 'decimal' ]);

        $setup->getConnection()->addColumn(
            $setup->getTable('quote_item'),
            'shipping_surcharge',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'nullable' => true,
                'comment' => 'Shipping surcharge'
            ]
        );
    }
}