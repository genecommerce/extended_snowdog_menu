<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Api\Data;

use Snowdog\Menu\Api\Data\NodeInterface as SnowdogNodeInterface;

interface NodeInterface extends SnowdogNodeInterface
{
    const FONT_COLOR = 'font_color';
    const FONT_WEIGHT = 'font_weight';

    /**
     * Get font color
     *
     * @return mixed
     */
    public function getFontColor();

    /**
     * Set font color
     *
     * @param $fontColor
     * @return mixed
     */
    public function setFontColor($fontColor);

    /**
     * Get font weight
     *
     * @return mixed
     */
    public function getFontWeight();

    /**
     * Set font weight
     *
     * @param $fontWeight
     * @return mixed
     */
    public function setFontWeight($fontWeight);
}
