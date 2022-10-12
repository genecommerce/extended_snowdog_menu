<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $setup->getConnection()->addColumn(
            $setup->getTable('snowmenu_node'),
            'font_color',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'classes',
                'comment' => 'Font Color'
            ]
        );

        $setup->getConnection()->addColumn(
            $setup->getTable('snowmenu_node'),
            'font_weight',
            [
                'type' => Table::TYPE_TEXT,
                'length' => 255,
                'nullable' => true,
                'after' => 'font_color',
                'comment' => 'Font Weight'
            ]
        );

        $installer->endSetup();
    }
}
