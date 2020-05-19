<?php

namespace Omnipay\FAC\Message\Request;

use Omnipay\FAC\Message\Response\HostedPurchaseResponse;

/**
 * Class PurchaseRequest
 * @package Omnipay\FAC\Message\Request
 *
 * @method HostedPurchaseResponse send()
 */
class HostedPurchaseRequest extends AbstractHostedPageRequest
{

    /**
     * @var string;
     */
    protected $requestName = 'HostedPageAuthorize';
    protected $endpointName = 'HostedPage';

    /**
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('acquirerId', 'merchantId', 'merchantPassword', 'orderNumber', 'transactionCode');

        // Mandatory fields
        $data['Request'] = [
            'TransactionDetails' => [
                'AcquirerId' => $this->getAcquirerId(),
                'Amount' => $this->formatAmount(),
                'Currency' => $this->getCurrencyNumeric(),
                'CurrencyExponent' => $this->getCurrencyDecimalPlaces(),
                'IPAddress' => $this->getClientIp(),
                'MerchantId' => $this->getMerchantId(),
                'OrderNumber' => $this->getTransactionId(),
                'Signature' => $this->generateSignature(),
                'SignatureMethod' => 'SHA1',
                'TransactionCode' => $this->getTransactionCode(),
            ],
            'CardHolderResponseURL' => $this->getReturnUrl(),
        ];

        return $data;
    }

    /**
     * Return the tokenize response object
     *
     * @param \SimpleXMLElement $xml Response xml object
     *
     * @return HostedPurchaseResponse
     */
    protected function newResponse($xml)
    {
        $data = json_decode(json_encode((array)$xml),true);
        $data = array_merge($this->getParameters(), $data);
        $response = new HostedPurchaseResponse($this, $data);
        $response->setRequestResultName($this->requestName);
        return $response;
    }
}
