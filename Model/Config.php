<?php
/**
 * See LICENSE.txt for license details.
 */

namespace MageWare\Common\Model;

class Config
{
    const XML_PATH_NOTIFICATION_ENABLED = 'mageware_common/notification/enabled';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isNotificationEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_NOTIFICATION_ENABLED);
    }
}
