<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block\Adminhtml\Edit\Tab;

use Magento\Backend\Block\Template;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Registry;
use Snowdog\Menu\Api\NodeRepositoryInterface;
use Snowdog\Menu\Block\Adminhtml\Edit\Tab\Nodes as SnowdogTabNodes;
use Snowdog\Menu\Controller\Adminhtml\Menu\Edit;
use Snowdog\Menu\Model\Menu\Node\Image\File as ImageFile;
use Snowdog\Menu\Model\NodeTypeProvider;
use Snowdog\Menu\Model\VueProvider;

class Nodes extends SnowdogTabNodes implements TabInterface
{
    const IMAGE_UPLOAD_URL = 'snowmenu/node/uploadimage';
    const IMAGE_DELETE_URL = 'snowmenu/node/deleteimage';

    protected $_template = 'Gene_ExtendedSnowdogMenu::menu/nodes.phtml';

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var NodeRepositoryInterface
     */
    private $nodeRepository;

    /**
     * @var ImageFile
     */
    private $imageFile;

    /**
     * Nodes constructor.
     * @param Template\Context $context
     * @param NodeRepositoryInterface $nodeRepository
     * @param ImageFile $imageFile
     * @param NodeTypeProvider $nodeTypeProvider
     * @param Registry $registry
     * @param VueProvider $vueProvider
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        NodeRepositoryInterface $nodeRepository,
        ImageFile $imageFile,
        NodeTypeProvider $nodeTypeProvider,
        Registry $registry,
        VueProvider $vueProvider,
        array $data = []
    ) {
        parent::__construct($context, $nodeRepository, $imageFile, $nodeTypeProvider, $registry, $vueProvider, $data);
        $this->registry = $registry;
        $this->nodeRepository = $nodeRepository;
        $this->imageFile = $imageFile;
    }

    /**
     * @return array
     */
    public function renderNodes()
    {
        $menu = $this->registry->registry(Edit::REGISTRY_CODE);
        $data = [];
        if ($menu) {
            $nodes = $this->nodeRepository->getByMenu($menu->getId());
            if (!empty($nodes)) {
                foreach ($nodes as $node) {
                    $level = $node->getLevel();
                    $parent = $node->getParentId() ?: 0;
                    if (!isset($data[$level])) {
                        $data[$level] = [];
                    }
                    if (!isset($data[$level][$parent])) {
                        $data[$level][$parent] = [];
                    }
                    $data[$level][$parent][] = $node;
                }
                return $this->renderNodeList(0, null, $data);
            }
        }
        return $data;
    }

    /**
     * @param $level
     * @param $parent
     * @param $data
     * @return array|bool
     */
    private function renderNodeList($level, $parent, $data)
    {
        if ($parent === null) {
            $parent = 0;
        }
        if (empty($data[$level])) {
            return false;
        }
        if (empty($data[$level][$parent])) {
            return false;
        }
        $nodes = $data[$level][$parent];
        foreach ($nodes as $node) {
            $menu[] = [
                'is_active' => $node->getIsActive(),
                'is_stored' => true,
                'type' => $node->getType(),
                'content' => $node->getContent(),
                'classes' => $node->getClasses(),
                'font_color' => $node->getFontColor(),
                'font_weight' => $node->getFontWeight(),
                'target' => $node->getTarget(),
                'node_template' => $node->getNodeTemplate(),
                'submenu_template' => $node->getSubmenuTemplate(),
                'id' => $node->getId(),
                'title' => $node->getTitle(),
                'image' => $node->getImage(),
                'image_url' => $node->getImage() ? $this->imageFile->getUrl($node->getImage()) : null,
                'image_alt_text' => $node->getImageAltText(),
                'columns' => $this->renderNodeList($level + 1, $node->getId(), $data) ?: [],
                'selected_item_id' => $node->getSelectedItemId()
            ];
        }
        return $menu;
    }
}
