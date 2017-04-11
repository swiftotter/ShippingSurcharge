<?php
/**
 * @by SwiftOtter, Inc. 4/11/17
 * @website https://swiftotter.com
 **/

namespace SwiftOtter\ShippingSurcharge\Setup;

use Magento\Framework\DB\Ddl\Table;
use \Magento\Framework\Setup\ModuleDataSetupInterface;
use \SwiftOtter\ShippingSurcharge\Model\Surcharge;

class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), 1.6) === ModuleDataSetupInterface::VERSION_COMPARE_LOWER) {
            $installer->getConnection()->addColumn($installer->getTable('quote'), Surcharge::SURCHARGE, [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'default' => '0.0000',
                'comment' => 'Total Surcharge Amount'
            ]);
            $installer->getConnection()->addColumn($installer->getTable('quote_item'), Surcharge::SURCHARGE, [
                'type' => Table::TYPE_DECIMAL,
                'length' => '12,4',
                'default' => null,
                'comment' => 'Surcharge Amount'
            ]);
        }

        $installer->endSetup();
    }
}