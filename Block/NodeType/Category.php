<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block\NodeType;

use Gene\ExtendedSnowdogMenu\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Snowdog\Menu\Model\NodeType\Category as SnowdogCategory;
use Snowdog\Menu\Block\NodeType\Category as SnowdogCategoryBlock;
use Snowdog\Menu\Model\TemplateResolver;

class Category extends SnowdogCategoryBlock
{
    protected $defaultTemplate = 'Snowdog_Menu::menu/node_type/category.phtml';

    /**
     * @var Data
     */
    private $helper;

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
        Data $helper,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $categoryModel, $templateResolver, $data);
        $this->helper = $helper;
    }

    /**
     * @param int $nodeId
     * @return bool|false|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageThumb(int $nodeId)
    {
        $category = $this->getCategory($nodeId);
        if (!$category) {
            return false;
        }
        return $this->helper->getImageThumb($category);
    }
}
