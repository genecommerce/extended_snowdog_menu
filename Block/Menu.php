<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block;

use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\Search\SearchCriteriaFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Escaper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Event\Manager as EventManager;
use Snowdog\Menu\Api\MenuRepositoryInterface;
use Snowdog\Menu\Api\NodeRepositoryInterface;
use Snowdog\Menu\Block\Menu as SnowdogBlockMenu;
use Snowdog\Menu\Model\Menu\Node\Image\File as ImageFile;
use Snowdog\Menu\Model\NodeTypeProvider;
use Snowdog\Menu\Model\TemplateResolver;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Menu extends SnowdogBlockMenu implements IdentityInterface
{
    /**
     * @var ImageFile
     */
    private $imageFile;

    /**
     * Menu constructor.
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     * @param Template\Context $context
     * @param EventManager $eventManager
     * @param MenuRepositoryInterface $menuRepository
     * @param NodeRepositoryInterface $nodeRepository
     * @param NodeTypeProvider $nodeTypeProvider
     * @param SearchCriteriaFactory $searchCriteriaFactory
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param TemplateResolver $templateResolver
     * @param ImageFile $imageFile
     * @param Escaper $escaper
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
        array $data = []
    ) {
        parent::__construct($context, $eventManager, $menuRepository, $nodeRepository, $nodeTypeProvider, $searchCriteriaFactory, $filterGroupBuilder, $templateResolver, $imageFile, $escaper, $data);
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
}
