<?php

namespace Omnipay\FAC\Message\Request;


use Omnipay\Common\Message\ResponseInterface;

/**
 * Class AbstractHostedPageRequest
 * @package Omnipay\FAC\Message\Request
 */
abstract class AbstractHostedPageRequest extends AbstractRequest
{
    /**
     * FACPG2 live endpoint URL
     *
     * @var string
     */
    protected $liveEndpoint = 'https://marlin.firstatlanticcommerce.com/PGService/';

    /**
     * FACPG2 test endpoint URL
     *
     * @var string
     */
    protected $testEndpoint = 'https://ecm.firstatlanticcommerce.com/PGService/';

    /**
     * FACPG2 XML namespace
     *
     * @var string
     */
    protected $namespace = 'http://schemas.firstatlanticcommerce.com/gateway/data';

    /**
     * FACPG2 XML root
     *
     * @var string
     */
    protected $requestName;


    /**
     * Transaction code (flag as a authorization)
     *
     * @var int;
     */
    protected $transactionCode = 0;

    /**
     * Returns the signature for the request.
     *
     * @return string base64 encoded sha1 hash of the merchantPassword, merchantId,
     *    acquirerId, transactionId, amount and currency code.
     */
    protected function generateSignature()
    {
        $signature  = $this->getMerchantPassword();
        $signature .= $this->getMerchantId();
        $signature .= $this->getAcquirerId();
        $signature .= $this->getTransactionId();
        $signature .= $this->formatAmount();
        $signature .= $this->getCurrencyNumeric();

        return base64_encode( sha1($signature, true) );
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
     * Return the response object
     *
     * @param \SimpleXMLElement $xml Response xml object
     *
     * @return ResponseInterface
     */
    abstract protected function newResponse($xml);

    /**
     * Send the request payload
     *
     * @param array $data Request payload
     *
     * @return ResponseInterface
     */
    public function sendData($data)
    {
        // Ensure you append the ?wsdl query string to the URL
        $wsdlurl = $this->getEndpoint().$this->endpointName.'.svc?wsdl';
        $soapUrl = $this->getEndpoint().$this->endpointName.'.svc';

        $options = array(
            'location' => $soapUrl,
            'soap_version'=> SOAP_1_1,
            'exceptions'=>0,
            'trace'=>1,
            'cache_wsdl'=>WSDL_CACHE_NONE
        );

        $client = new \SoapClient($wsdlurl, $options);
        $result = $client->{$this->requestName}($data);

        return $this->response = $this->newResponse( $result );
    }

    /**
     * @param string $value set PageSet
     * @return $this
     */
    public function setPageSet($value)
    {
        return $this->setParameter('pageSet', $value);
    }

    /**
     * @return string
     */
    public function getPageSet()
    {
        return $this->getParameter('pageSet');
    }

    /**
     * @param  string  $value  pageName
     * @return $this
     */
    public function setPageName($value)
    {
        return $this->setParameter('pageName', $value);
    }

    /**
     * @return string
     */
    public function getPageName()
    {
        return $this->getParameter('pageName');
    }

    /**
     * @param string $value TransactionCode
     * @return $this
     */
    public function setTransactionCode($value)
    {
        return $this->setParameter('transactionCode', $value);
    }

    /**
     * Returns the transaction code based on the AVS check requirement
     *
     * @return int Transaction Code
     */
    protected function getTransactionCode()
    {
        $transactionCode = $this->transactionCode;
        if($this->getRequireAvsCheck()) {
            $transactionCode += 1;
        }
        if($this->getCreateCard()) {
            $transactionCode += 128;
        }
        return $transactionCode;
    }

    /**
     * @param boolean $value Create a tokenized card on FAC during an authorize request
     *
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function setCreateCard($value)
    {
        return $this->setParameter('createCard', $value);
    }

    /**
     * @return boolean Create a tokenized card on FAC during an authorize request
     */
    public function getCreateCard()
    {
        return $this->getParameter('createCard');
    }
}
