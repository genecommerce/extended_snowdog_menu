<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Block\NodeType;

use Magento\Framework\View\Element\Template;
use Snowdog\Menu\Api\NodeTypeInterface;
use Snowdog\Menu\Block\NodeType\AbstractNode as SnowdogAbstractNode;
use Snowdog\Menu\Model\TemplateResolver;

abstract class AbstractNode extends SnowdogAbstractNode implements NodeTypeInterface
{
    const FONT_COLOR_CODE = 'node_font_color';
    const FONT_WEIGHT_CODE = 'node_font_weight';

    /**
     * @inheritDoc
     */
    public function __construct(
        Template\Context $context,
        TemplateResolver $templateResolver,
        array $data = []
    ) {
        parent::__construct($context, $templateResolver, $data);
        $this->addNodeAttribute(self::FONT_COLOR_CODE, 'Node Font color', 'text');
        $this->addNodeAttribute(self::FONT_WEIGHT_CODE, 'Node Font weight', 'text');
    }
}
