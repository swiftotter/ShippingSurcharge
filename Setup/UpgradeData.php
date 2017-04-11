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

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\{
    ModuleDataSetupInterface,
    ModuleContextInterface,
    UpgradeDataInterface
};
use Magento\Sales\Setup\{
    SalesSetup,
    SalesSetupFactory
};
use \SwiftOtter\ShippingSurcharge\Model\Surcharge;

class UpgradeData implements UpgradeDataInterface
{
    private $blockFactory;
    /**
     * @var \Magento\Sales\Setup\SalesSetupFactory
     */
    private $salesSetupFactory;

    public function __construct(
        SalesSetupFactory $salesSetupFactory,
        BlockFactory $blockFactory
    )
    {
        $this->salesSetupFactory = $salesSetupFactory;
        $this->blockFactory = $blockFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var SalesSetup $salesSetup */
        $salesSetup = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

        if (version_compare($context->getVersion(), 1.2) === ModuleDataSetupInterface::VERSION_COMPARE_LOWER) {
            $salesSetup->addAttribute('order', Surcharge::BASE_SURCHARGE, ['type' => 'decimal' ]);
            $salesSetup->addAttribute('order', Surcharge::SURCHARGE, ['type' => 'decimal' ]);
            $salesSetup->addAttribute('order_item', Surcharge::BASE_SURCHARGE, ['type' => 'decimal' ]);
            $salesSetup->addAttribute('order_item', Surcharge::SURCHARGE, ['type' => 'decimal' ]);
        }

        if (version_compare($context->getVersion(), 1.3) === ModuleDataSetupInterface::VERSION_COMPARE_LOWER) {
            $this->blockFactory->create()->setData((new Definition\ExplanatoryNoteStaticBlock())->getData())->save();
        }

        if (version_compare($context->getVersion(), 1.6) === ModuleDataSetupInterface::VERSION_COMPARE_LOWER) {
            $salesSetup = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);
            $salesSetup->addAttribute('invoice', Surcharge::BASE_SURCHARGE, ['type' => 'decimal']);
            $salesSetup->addAttribute('invoice', Surcharge::SURCHARGE, ['type' => 'decimal']);
            $salesSetup->addAttribute('invoice_item', Surcharge::BASE_SURCHARGE, ['type' => 'decimal']);
            $salesSetup->addAttribute('invoice_item', Surcharge::SURCHARGE, ['type' => 'decimal']);

            $salesSetup->addAttribute('creditmemo', Surcharge::BASE_SURCHARGE, ['type' => 'decimal']);
            $salesSetup->addAttribute('creditmemo', Surcharge::SURCHARGE, ['type' => 'decimal']);
            $salesSetup->addAttribute('creditmemo_item', Surcharge::BASE_SURCHARGE, ['type' => 'decimal']);
            $salesSetup->addAttribute('creditmemo_item', Surcharge::SURCHARGE, ['type' => 'decimal']);
        }
    }
}
