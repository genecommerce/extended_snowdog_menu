<?php
declare(strict_types=1);

namespace Gene\ExtendedSnowdogMenu\ViewModel;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\ScopeInterface;

class MenuStyle implements ArgumentInterface
{
    const XML_PATH_MOBILE_MENU_STYLE = 'gene_snowdog_menu/general/mobile_menu_style';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * MenuStyle constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param null $storeId
     * @return string
     */
    public function getMenuStyle($storeId = null)
    {
        return (string)$this->scopeConfig->getValue(
            self::XML_PATH_MOBILE_MENU_STYLE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
