<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\Observer;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Store\Model\ScopeInterface;

class RemoveNavigationBlock implements ObserverInterface
{
    const XML_PATH_MENU_ENABLED = 'gene_snowdog_menu/general/enabled';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * RemoveNavigationBlock constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $layout = $observer->getLayout();

        if ($this->isMenuEnabled()) {
            $layout->unsetElement('catalog.topnav');
        }
    }

    /**
     * @return mixed
     */
    public function isMenuEnabled()
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_MENU_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }
}
