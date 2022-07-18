<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Model\System\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Phrase;

class MenuStyleOptions implements OptionSourceInterface
{
    /**
     * @return array<int, array<string, Phrase|string>>
     */
    public function toOptionArray(): array
    {
        return [['value' => 'expand', 'label' => __('Expand')], ['value' => 'slide', 'label' => __('Slide')]];
    }

    /**
     * @return array<string,Phrase>
     */
    public function toArray(): array
    {
        return ['expand' => __('Expand'), 'slide' => __('Slide')];
    }
}
