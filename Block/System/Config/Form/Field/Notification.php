<?php

namespace MageWare\Common\Block\System\Config\Form\Field;

class Notification extends \Magento\Config\Block\System\Config\Form\Field
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface
     */
    protected $dateTimeFormatter;

    /**
     * @var \MageWare\Common\Model\Notification
     */
    protected $notification;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface $dateTimeFormatter
     * @param \MageWare\Common\Model\Notification $notification
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface $dateTimeFormatter,
        \MageWare\Common\Model\Notification $notification,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dateTimeFormatter = $dateTimeFormatter;
        $this->notification = $notification;
    }

    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(
        \Magento\Framework\Data\Form\Element\AbstractElement $element
    ) {
        $timestamp = $this->notification->getLastUpdate();
        if (!$timestamp) {
            return 'N/A';
        }
        $element->setValue($timestamp);
        $format = $this->_localeDate->getDateTimeFormat(
            \IntlDateFormatter::MEDIUM
        );
        return $this->dateTimeFormatter->formatObject($this->_localeDate->date(intval($element->getValue())), $format);
    }
}
