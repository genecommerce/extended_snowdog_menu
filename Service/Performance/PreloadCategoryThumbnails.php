<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Service\Performance;

use Gene\ExtendedSnowdogMenu\Model\ResourceModel\AttributeValues;
use Magento\Store\Model\StoreManagerInterface;

class PreloadCategoryThumbnails
{
    private $lookup = [];

    public function __construct(
        private readonly AttributeValues $attributeValue,
        private readonly StoreManagerInterface $storeManager,
    ) {

    }

    /**
     * Bulk preload the category IDs
     *
     * @param array $categoryIds
     * @return void
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function loadCategoryThumbnails(array $categoryIds)
    {
        if (empty($categoryIds)) {
            return;
        }

        $values = $this->attributeValue->getValues(
            \Magento\Catalog\Api\Data\CategoryInterface::class,
            $categoryIds,
            ['menu_image_thumb'],
            [$this->storeManager->getStore()->getId(), '0']
        );

        if (!empty($values)) {
            foreach ($values as $row) {
                if (isset($row['value'])) {
                    if (isset($row['row_id'])) {
                        $this->lookup[$row['row_id']] = $row['value'];
                    } else {
                        $this->lookup[$row['entity_id']] = $row['value'];
                    }
                }
            }
        }
    }

    /**
     * @param $categoryId
     * @return string|null
     */
    public function getThumbnailForCategoryId($categoryId)
    {
        return $this->lookup[$categoryId] ?? null;
    }
}
