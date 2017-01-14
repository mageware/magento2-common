<?php
/**
 * See LICENSE.txt for license details.
 */

namespace MageWare\Common\Model;

class Feed
{
    const ENDPOINT = 'http://notifications.mageware.com/feed.rss';

    /**
     * @var \Magento\Framework\HTTP\Adapter\CurlFactory
     */
    protected $curlFactory;

    /**
     * @var \Magento\Framework\App\DeploymentConfig
     */
    protected $deploymentConfig;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * @param \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory
     * @param \Magento\Framework\App\DeploymentConfig $deploymentConfig
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     */
    public function __construct(
        \Magento\Framework\HTTP\Adapter\CurlFactory $curlFactory,
        \Magento\Framework\App\DeploymentConfig $deploymentConfig,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    ) {
        $this->curlFactory = $curlFactory;
        $this->deploymentConfig = $deploymentConfig;
        $this->productMetadata = $productMetadata;
    }

    /**
     * @return array|bool
     */
    public function getFeedData()
    {
        $feedXml = $this->getFeedXml();
        $feedData = [];

        if (!($feedXml instanceof \SimpleXMLElement)) {
            return false;
        }

        $installDate = strtotime(
            $this->deploymentConfig->get(
                \Magento\Framework\Config\ConfigOptionsListConstants::CONFIG_PATH_INSTALL_DATE
            )
        );

        if ($feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $pubDate = strtotime((string) $item->pubDate);
                if ($installDate <= $pubDate) {
                    $feedData[] = [
                        'url' => (string) $item->link,
                        'description' => (string) $item->description,
                        'title' => (string) $item->title,
                        'date_added' => date('Y-m-d H:i:s', $pubDate),
                        'severity' => (int) $item->severity,
                    ];
                }
            }
        }

        return $feedData;
    }

    /**
     * Retrieve feed data as XML element
     *
     * @return \SimpleXMLElement|bool
     */
    protected function getFeedXml()
    {
        $client = $this->curlFactory->create();
        $client->setConfig($this->getCurlConfig());

        $client->write(\Zend_Http_Client::GET, self::ENDPOINT, '1.0');

        $response = $client->read();
        $client->close();

        if (false !== $response) {
            $data = preg_split('/^\r?$/m', $response, 2);

            try {
                $xml = new \SimpleXMLElement(trim($data[1]));
            } catch (\Exception $e) {
                return false;
            }

            return $xml;
        }

        return false;
    }

    /**
     * Retrieve config for curl
     *
     * @return array
     */
    protected function getCurlConfig()
    {
        $useragent = $this->productMetadata->getName()
            . '/' . $this->productMetadata->getVersion()
            . ' (' . $this->productMetadata->getEdition() . ')';

        return [
            'useragent' => $useragent,
            'timeout'   => 2
        ];
    }
}
