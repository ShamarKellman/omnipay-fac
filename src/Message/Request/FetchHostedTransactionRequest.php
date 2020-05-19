<?php

namespace Omnipay\FAC\Message\Request;


use Omnipay\FAC\Message\Response\FetchHostedTransactionResponse;

/**
 * Class FetchTransactionRequest
 * @package Omnipay\Paynl\Message\Request
 *
 * @method FetchHostedTransactionResponse send()
 */
class FetchHostedTransactionRequest extends AbstractHostedPageRequest
{

    /**
     * @var string;
     */
    protected $requestName = 'TransactionStatus';
    protected $endpointName = 'Services';

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('acquirerId', 'merchantId', 'merchantPassword', 'orderNumber');

        // Mandatory fields
        $data['Request'] = [
            'AcquirerId' => $this->getAcquirerId(),
            'MerchantId' => $this->getMerchantId(),
            'OrderNumber' => $this->getTransactionId(),
            'Password' => $this->getMerchantPassword(),
        ];
        return $data;
    }

    /**
     * Return the tokenize response object
     *
     * @param \SimpleXMLElement $xml Response xml object
     *
     * @return FetchHostedTransactionResponse
     */
    protected function newResponse($xml)
    {
        $data = json_decode(json_encode((array)$xml), true);
        $data = array_merge($this->getParameters(), $data);
        $response = new FetchHostedTransactionResponse($this, $data);
        $response->setRequestResultName($this->requestName);
        return $response;
    }
}
