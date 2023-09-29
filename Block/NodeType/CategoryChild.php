<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block\NodeType;

use Snowdog\Menu\Block\NodeType\CategoryChild as SnowdogCategoryChild;

class CategoryChild extends SnowdogCategoryChild
{
    /**
     * @var string
     */
    protected $defaultTemplate = 'Snowdog_Menu::menu/node_type/category_child.phtml';

    /**
     * @return array
     */
    public function getNodeCacheKeyInfo()
    {
        return [];
    }
}
