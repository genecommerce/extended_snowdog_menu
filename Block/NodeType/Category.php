<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block\NodeType;

use Gene\ExtendedSnowdogMenu\Helper\Data;
use Magento\Eav\Model\ResourceModel\AttributeValue;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\StoreManagerInterface;
use Snowdog\Menu\Model\NodeType\Category as SnowdogCategory;
use Snowdog\Menu\Block\NodeType\Category as SnowdogCategoryBlock;
use Snowdog\Menu\Model\TemplateResolver;

class Category extends SnowdogCategoryBlock
{
    protected $defaultTemplate = 'Snowdog_Menu::menu/node_type/category.phtml';

    /**
     * CategoryMenuImage constructor.
     * @param Context $context
     * @param Registry $coreRegistry
     * @param SnowdogCategory $categoryModel
     * @param TemplateResolver $templateResolver
     * @param Data $helper
     * @param AttributeValue $attributeValue
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        SnowdogCategory $categoryModel,
        TemplateResolver $templateResolver,
        private readonly Data $helper,
        private readonly AttributeValue $attributeValue,
        private readonly StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $categoryModel, $templateResolver, $data);
    }

    /**
     * @param int $nodeId
     * @return bool|false|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageThumb(int $nodeId)
    {
        return $this->getCategoryThumb($this->getCategoryId($nodeId));
    }

    /**
     * @param int $nodeId
     * @return int
     */
    protected function getCategoryId(int $nodeId)
    {
        if (!isset($this->nodes[$nodeId])) {
            throw new \InvalidArgumentException('Invalid node identifier specified');
         }
        $node = $this->nodes[$nodeId];
        return (int) $node->getContent();
    }

    protected function getCategoryThumb(int $categoryId)
    {
        $values = $this->attributeValue->getValues(
            \Magento\Catalog\Api\Data\CategoryInterface::class,
            $categoryId,
            ['menu_image_thumb'],
            [$this->storeManager->getStore()->getId(), '0']
        );
        if (!empty($values)) {
            foreach ($values as $row) {
                if (isset($row['value'])) {
                    $result = $row['value'];
                    break;
                }
            }
        }
        return $result ?? null;
     }
}
