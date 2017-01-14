<?php
/**
 * See LICENSE.txt for license details.
 */

namespace MageWare\Common\Model;

class Notification
{
    const CACHE_TAG = 'mageware_common_notification_last_update';

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * @param \Magento\Framework\App\CacheInterface $cache
     */
    public function __construct(
        \Magento\Framework\App\CacheInterface $cache
    ) {
        $this->cache = $cache;
    }

    /**
     * Get last update timestamp
     *
     * @return string
     */
    public function getLastUpdate()
    {
        return $this->cache->load(
            self::CACHE_TAG
        );
    }

    /**
     * Set last update timestamp
     *
     * @param int $timestamp
     * @return $this
     */
    public function setLastUpdate($timestamp)
    {
        $this->cache->save(
            $timestamp,
            self::CACHE_TAG
        );

        return $this;
    }
}
