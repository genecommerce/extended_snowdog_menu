<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Model\Menu;

use Magento\Framework\DataObject\IdentityInterface;
use Gene\ExtendedSnowdogMenu\Api\Data\NodeInterface;
use Snowdog\Menu\Model\Menu\Node as SnowdogNode;

class Node extends SnowdogNode implements NodeInterface, IdentityInterface
{
    /**
     * @inheritdoc
     */
    public function getFontColor()
    {
        return $this->_getData(NodeInterface::FONT_COLOR);
    }

    /**
     * @inheritdoc
     */
    public function setFontColor($fontColor)
    {
        return $this->setData(NodeInterface::FONT_COLOR, $fontColor);
    }

    /**
     * @inheritdoc
     */
    public function getFontWeight()
    {
        return $this->_getData(NodeInterface::FONT_WEIGHT);
    }

    /**
     * @inheritdoc
     */
    public function setFontWeight($fontWeight)
    {
        return $this->setData(NodeInterface::FONT_WEIGHT, $fontWeight);
    }
}
