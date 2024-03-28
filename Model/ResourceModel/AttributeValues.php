<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Model\ResourceModel;

use Magento\Eav\Model\Config;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;
use Magento\Framework\DB\Sql\UnionExpression;
use Magento\Framework\EntityManager\MetadataPool;

class AttributeValues
{
    /**
     * @var MetadataPool
     */
    private $metadataPool;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;
    /**
     * @var Config
     */
    private $config;

    /**
     * @param ResourceConnection $resourceConnection
     * @param MetadataPool $metadataPool
     * @param Config $config
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        MetadataPool $metadataPool,
        Config $config
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->metadataPool = $metadataPool;
        $this->config = $config;
    }

    /**
     * Get attribute values for given entity type, entity IDs, attribute codes and store IDs
     *
     * @see \Magento\Eav\Model\ResourceModel\AttributeValue::getValues()
     *
     * This function is 99% the same as the above, except we have support for multiple entity_ids
     *
     * @param string $entityType
     * @param array $entityId
     * @param string[] $attributeCodes
     * @param int[] $storeIds
     * @return array
     */
    public function getValues(
        string $entityType,
        array $entityIds,
        array $attributeCodes = [],
        array $storeIds = []
    ): array {
        $metadata = $this->metadataPool->getMetadata($entityType);
        $connection = $metadata->getEntityConnection();
        $selects = [];
        $attributeTables = [];
        $attributes = [];
        $allAttributes = $this->getEntityAttributes($entityType);
        $result = [];
        if ($attributeCodes) {
            foreach ($attributeCodes as $attributeCode) {
                $attributes[$attributeCode] = $allAttributes[$attributeCode];
            }
        } else {
            $attributes = $allAttributes;
        }

        foreach ($attributes as $attribute) {
            if (!$attribute->isStatic()) {
                $attributeTables[$attribute->getBackend()->getTable()][] = $attribute->getAttributeId();
            }
        }

        if ($attributeTables) {
            foreach ($attributeTables as $attributeTable => $attributeIds) {
                $select = $connection->select()
                    ->from(
                        ['t' => $attributeTable],
                        ['*']
                    )
                    ->where($metadata->getLinkField() . ' in (?)', $entityIds)
                    ->where('attribute_id IN (?)', $attributeIds);
                if (!empty($storeIds)) {
                    $select->where(
                        'store_id IN (?)',
                        $storeIds
                    );
                }
                $selects[] = $select;
            }

            if (count($selects) > 1) {
                $select = $connection->select();
                $select->from(['u' => new UnionExpression($selects, Select::SQL_UNION_ALL, '( %s )')]);
            } else {
                $select = reset($selects);
            }

            $result = $connection->fetchAll($select);
        }

        return $result;
    }

    /**
     * Get attribute of given entity type
     *
     * @param string $entityType
     * @return array
     */
    private function getEntityAttributes(string $entityType): array
    {
        $metadata = $this->metadataPool->getMetadata($entityType);
        $eavEntityType = $metadata->getEavEntityType();
        return null === $eavEntityType ? [] : $this->config->getEntityAttributes($eavEntityType);
    }
}
