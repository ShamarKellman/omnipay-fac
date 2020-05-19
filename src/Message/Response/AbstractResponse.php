<?php

namespace Omnipay\FAC\Message\Response;

use Omnipay\Common\Message\AbstractResponse as BaseAbstractResponse;
use SimpleXMLElement;

abstract class AbstractResponse extends BaseAbstractResponse
{
    /**
     * FACPG2 live endpoint URL
     *
     * @var string
     */
    protected $liveEndpoint = 'https://marlin.firstatlanticcommerce.com/';

    /**
     * FACPG2 test endpoint URL
     *
     * @var string
     */
    protected $testEndpoint = 'https://ecm.firstatlanticcommerce.com/';

    /**
     * FACPG2 XML root
     *
     * @var string
     */
    public $requestResultName;

    /**
     * Seserializes XML to an array
     *
     * @param \SimpleXMLElement|string $xml SimpleXMLElement object or a well formed xml string.
     *
     * @return array data
     */
    protected function xmlDeserialize($xml)
    {
        $array = [];

        if (!$xml instanceof SimpleXMLElement)
        {
            $xml = new SimpleXMLElement($xml);
        }

        foreach ($xml->children() as $key => $child)
        {
            $value = (string) $child;
            $_children = $this->xmlDeserialize($child);
            $_push = ( $_hasChild = ( count($_children) > 0 ) ) ? $_children : $value;

            if ( $_hasChild && !empty($value) && $value !== '' )
            {
                $_push[] = $value;
            }

            $array[$key] = $_push;
        }

        return $array;
    }

    /**
     * This is mostly for convenience so you can get the Transaction ID from the response which FAC sends back with all
     * of their responses except the Create Card. If you call this from CreateCardResponse, you will just get a null.
     *
     * @return null
     */
    public function getTransactionId()
    {
        return isset($this->data['OrderNumber']) ? $this->data['OrderNumber'] : null;
    }

    public function setRequestResultName($value) {
        $this->requestResultName = $value. 'Result';
    }

    /**
     * Returns the live or test endpoint depending on TestMode.
     *
     * @return string Endpoint URL
     */
    protected function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * @return string
     */
    public function getTestMode()
    {
        return isset($this->data['testMode']) ? $this->data['testMode'] : false;
    }
}
