<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block\NodeType;

use Snowdog\Menu\Block\NodeType\CmsPage as SnowdogCmsPage;

class CmsPage extends SnowdogCmsPage
{
    /**
     * @var string
     */
    protected $defaultTemplate = 'Snowdog_Menu::menu/node_type/cms_page.phtml';

    /**
     * @return array
     */
    public function getNodeCacheKeyInfo()
    {
        return [];
    }
}
