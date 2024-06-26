<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block\NodeType;

use Gene\ExtendedSnowdogMenu\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Snowdog\Menu\Model\NodeType\Category as SnowdogCategory;
use Snowdog\Menu\Block\NodeType\Category as SnowdogCategoryBlock;
use Snowdog\Menu\Model\TemplateResolver;
use Gene\ExtendedSnowdogMenu\Service\Performance\PreloadCategoryThumbnails;

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
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        SnowdogCategory $categoryModel,
        TemplateResolver $templateResolver,
        private readonly Data $helper,
        private readonly PreloadCategoryThumbnails $preloadCategoryThumbnails,
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
        $categoryId = $this->getCategoryId($nodeId);
        return $this->getCategoryThumb($categoryId);
    }

    /**
     * @param int $nodeId
     * @return int
     */
    protected function getCategoryId(int $nodeId)
    {
        // fix to retrieve row_id for attribute value lookup
        if ($category = $this->getCategory($nodeId)) {
            return (int) $category->getRowId();
        }
        if (!isset($this->nodes[$nodeId])) {
            throw new \InvalidArgumentException('Invalid node identifier specified');
         }
        $node = $this->nodes[$nodeId];
        return (int) $node->getContent();
    }

    protected function getCategoryThumb(int $categoryId)
    {
        return $this->preloadCategoryThumbnails->getThumbnailForCategoryId($categoryId);
    }

    /**
     * @return array
     */
    public function getNodeCacheKeyInfo()
    {
        return [];
    }
}
