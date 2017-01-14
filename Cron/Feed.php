<?php
/**
 * See LICENSE.txt for license details.
 */

namespace MageWare\Common\Cron;

class Feed
{
    /**
     * @var \Magento\AdminNotification\Model\InboxFactory
     */
    protected $inboxFactory;

    /**
     * @var \MageWare\Common\Model\Notification
     */
    protected $notification;

    /**
     * @var \MageWare\Common\Model\Config
     */
    protected $config;

    /**
      * @var \MageWare\Common\Model\Feed
      */
    protected $feed;

    /**
     * @param \Magento\AdminNotification\Model\InboxFactory $inboxFactory*
     * @param \MageWare\Common\Model\Notification $notification
     * @param \MageWare\Common\Model\Config $config
     * @param \MageWare\Common\Model\Feed $feed
     */
    public function __construct(
        \Magento\AdminNotification\Model\InboxFactory $inboxFactory,
        \MageWare\Common\Model\Notification $notification,
        \MageWare\Common\Model\Config $config,
        \MageWare\Common\Model\Feed $feed
    ) {
        $this->inboxFactory = $inboxFactory;
        $this->notification = $notification;
        $this->config = $config;
        $this->feed = $feed;
    }

    /**
     * @return void
     */
    public function execute()
    {
        if ($this->config->isNotificationEnabled()) {
            $data = $this->feed->getFeedData();

            if (is_array($data)) {
                $this->inboxFactory
                    ->create()
                    ->parse(array_reverse($data));
            }

            if (false !== $data) {
                $this->notification->setLastUpdate(time());
            }
        }
    }
}
