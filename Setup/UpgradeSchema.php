<?php

namespace Magento\PagaCheckout\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $eavTable = $installer->getTable('quote');

        if (version_compare($context->getVersion(), '1.0.1') < 0) {

            $columns = [
                'paga_charge_reference' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => true,
                    'comment' => 'Paga Charge Reference',
                ],
            ];

            $connection = $installer->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($eavTable, $name, $definition);
            }
        }

        $installer->endSetup();
    }

}