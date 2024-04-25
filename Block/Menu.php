<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block;

use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\Search\SearchCriteriaFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Event\Manager as EventManager;
use Magento\Framework\View\Element\Template\Context;
use Snowdog\Menu\Api\MenuRepositoryInterface;
use Snowdog\Menu\Api\NodeRepositoryInterface;
use Snowdog\Menu\Block\Menu as SnowdogBlockMenu;
use Snowdog\Menu\Model\Menu\Node\Image\File as ImageFile;
use Snowdog\Menu\Model\NodeTypeProvider;
use Snowdog\Menu\Model\TemplateResolver;
use Gene\ExtendedSnowdogMenu\Service\Performance\PreloadCategoryThumbnails;
use Gene\ExtendedSnowdogMenu\Block\NodeType\Category as CategoryNode;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Menu extends SnowdogBlockMenu implements IdentityInterface
{
    /**
     * @var ImageFile
     */
    private $imageFile;

    private $bulkLoadedNodesData = false;

    /**
     * Menu constructor.
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @param Context $context
     * @param EventManager $eventManager
     * @param MenuRepositoryInterface $menuRepository
     * @param NodeRepositoryInterface $nodeRepository
     * @param NodeTypeProvider $nodeTypeProvider
     * @param SearchCriteriaFactory $searchCriteriaFactory
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param TemplateResolver $templateResolver
     * @param ImageFile $imageFile
     * @param Escaper $escaper
     * @param PreloadCategoryThumbnails $preloadCategoryThumbnails
     * @param CategoryNode $categoryNode
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        EventManager $eventManager,
        MenuRepositoryInterface $menuRepository,
        NodeRepositoryInterface $nodeRepository,
        NodeTypeProvider $nodeTypeProvider,
        SearchCriteriaFactory $searchCriteriaFactory,
        FilterGroupBuilder $filterGroupBuilder,
        TemplateResolver $templateResolver,
        ImageFile $imageFile,
        Escaper $escaper,
        private readonly PreloadCategoryThumbnails $preloadCategoryThumbnails,
        private readonly CategoryNode $categoryNode,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $eventManager,
            $menuRepository,
            $nodeRepository,
            $nodeTypeProvider,
            $searchCriteriaFactory,
            $filterGroupBuilder,
            $templateResolver,
            $imageFile,
            $escaper,
            $data
        );
        $this->imageFile = $imageFile;
    }

    /**
     * @param NodeRepositoryInterface $node
     * @return string
     */
    public function renderViewAllLink($node)
    {
        return $this->getMenuNodeBlock($node)
            ->setIsViewAllLink(true)
            ->toHtml();
    }

    /**
     * @param NodeRepositoryInterface $node
     * @return string
     */
    public function renderMenuNode($node)
    {
        return $this->getMenuNodeBlock($node)->toHtml();
    }

    /**
     * @param NodeRepositoryInterface $node
     * @return Template|\Snowdog\Menu\Api\NodeTypeInterface
     */
    private function getMenuNodeBlock($node)
    {
        $nodeBlock = $this->getNodeTypeProvider($node->getType());

        $level = $node->getLevel();
        $isRoot = 0 == $level;
        $nodeBlock->setId($node->getNodeId())
            ->setTitle($node->getTitle())
            ->setLevel($level)
            ->setIsRoot($isRoot)
            ->setIsParent((bool) $node->getIsParent())
            ->setIsViewAllLink(false)
            ->setContent($node->getContent())
            ->setNodeClasses($node->getClasses())
            ->setNodeFontColor($node->getFontColor())
            ->setNodeFontWeight($node->getFontWeight())
            ->setMenuClass($this->getMenu()->getCssClass())
            ->setMenuCode($this->getData('menu'))
            ->setTarget($node->getTarget())
            ->setImage($node->getImage())
            ->setImageUrl($node->getImage() ? $this->imageFile->getUrl($node->getImage()) : null)
            ->setImageAltText($node->getImageAltText())
            ->setCustomTemplate($node->getNodeTemplate())
            ->setAdditionalData($node->getAdditionalData());

        return $nodeBlock;
    }

    /**
     * @param $level
     * @param $parent
     * @return array|mixed
     */
    public function getNodes($level = 0, $parent = null)
    {
        $nodes = parent::getNodes($level, $parent);
        if ($level === 0 && !$this->bulkLoadedNodesData) {
            $this->bulkLoadedNodesData = true;
            $this->preloadCategoryThumbnails();
        }
        return $nodes;
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function preloadCategoryThumbnails()
    {
        $nodesTree = $this->getNodesTree();
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveArrayIterator($nodesTree),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $flattenedData = [];
        foreach ($iterator as $value) {
            if ($value instanceof \Snowdog\Menu\Model\Menu\Node) {
                if ($value->getType() === 'category') {
                    $category = $this->categoryNode->getCategory((int)$value->getNodeId());
                    $flattenedData[] = (int) $category->getRowId();
                } else {
                    $flattenedData[] = (int)$value->getContent();
                }
            }
        }
        $categoryIds = array_unique($flattenedData);
        $this->preloadCategoryThumbnails->loadCategoryThumbnails($categoryIds);
    }
}
