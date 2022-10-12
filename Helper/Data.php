<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Helper;

use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ATTR_MENU_IMAGE_THUMB = 'menu_image_thumb';
    const ATTR_MENU_IMAGE_THUMB_LABEL = 'Menu Thumbnail Image';

    /**
     * @var AttributeSetRepositoryInterface
     */
    private $attributeSet;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Data constructor.
     * @param Context $context
     * @param AttributeSetRepositoryInterface $attributeSet
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        AttributeSetRepositoryInterface $attributeSet,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->attributeSet = $attributeSet;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $category
     * @return false|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageThumb($category)
    {
        return $this->getFileUrl($category);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }

    /**
     * @param $filePath
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageUrl($filePath)
    {
        return $filePath;
    }

    /**
     * @param $category
     * @return bool|string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFileUrl($category)
    {
        $url = $category->getData(self::ATTR_MENU_IMAGE_THUMB);
        if (!empty($url)) {
            return $this->getImageUrl($url);
        }
        return false;
    }
}
