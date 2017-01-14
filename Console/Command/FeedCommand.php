<?php
/**
 * See LICENSE.txt for license details.
 */

namespace MageWare\Common\Console\Command;

class FeedCommand extends \Symfony\Component\Console\Command\Command
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
      * @var \MageWare\Common\Model\Feed
      */
    protected $feed;

    /**
     * @param \Magento\AdminNotification\Model\InboxFactory $inboxFactory
     * @param \MageWare\Common\Model\Notification $notification
     * @param \MageWare\Common\Model\Feed $feed
     */
    public function __construct(
        \Magento\AdminNotification\Model\InboxFactory $inboxFactory,
        \MageWare\Common\Model\Notification $notification,
        \MageWare\Common\Model\Feed $feed
    ) {
        parent::__construct();

        $this->inboxFactory = $inboxFactory;
        $this->notification = $notification;
        $this->feed = $feed;
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->setName('mageware_common:feed:update')
            ->setDescription('Update MageWare notification feed');
    }

    /**
     * @inheritdoc
     */
    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    ) {
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
