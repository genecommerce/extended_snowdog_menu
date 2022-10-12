<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Model;

use Magento\Framework\Filesystem;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class ImageUploader extends \Magento\Catalog\Model\ImageUploader
{
    /**
     * ImageUploader constructor.
     * @param Database $coreFileStorageDatabase
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param array $allowedMimeTypes
     */
    public function __construct(
        Database $coreFileStorageDatabase,
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        $allowedMimeTypes = []
    ) {
        parent::__construct(
            $coreFileStorageDatabase,
            $filesystem,
            $uploaderFactory,
            $storeManager,
            $logger,
            'catalog/tmp/category',
            'catalog/category',
            ['jpg', 'jpeg', 'gif', 'png'],
            $allowedMimeTypes
        );
    }
}
