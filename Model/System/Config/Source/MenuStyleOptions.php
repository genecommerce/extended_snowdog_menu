<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Model\System\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class MenuStyleOptions implements OptionSourceInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => 'expand', 'label' => __('Expand')], ['value' => 'slide', 'label' => __('Slide')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ['expand' => __('Expand'), 'slide' => __('Slide')];
    }
}
