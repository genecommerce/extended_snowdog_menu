<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Setup\Patch\Data;

use Magento\Catalog\Model\Category;
use Gene\ExtendedSnowdogMenu\Helper\Data;
use Magento\Catalog\Model\Category\Attribute\Backend\Image;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class MenuImageThumbCategoryAttribute implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * MenuImageThumbCategoryAttribute constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * @return DataPatchInterface|void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function apply()
    {
        /** @var \Magento\Eav\Setup\EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup->addAttribute(
            Category::ENTITY,
            Data::ATTR_MENU_IMAGE_THUMB,
            [
                'type' => 'varchar',
                'label' => Data::ATTR_MENU_IMAGE_THUMB_LABEL,
                'input' => 'image',
                'backend' => Image::class,
                'required' => false,
                'visible' => true,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'sort_order' => 10,
                'group' => 'General Information'
            ]
        );
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @return array|string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies()
    {
        return [];
    }
}
