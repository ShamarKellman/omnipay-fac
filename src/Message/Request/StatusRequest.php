<?php

namespace Omnipay\FAC\Message\Request;

use Omnipay\FAC\Message\Response\Response;

/**
 * FACPG2 Transaction Status Request
 *
 * Required parameters:
 * transactionId - corresponds to the merchant's transaction ID
 */
class StatusRequest extends AbstractRequest
{
    /**
     * @var string;
     */
    protected $requestName = 'TransactionStatusRequest';

    /**
     * Validate and construct the data for the request
     *
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('merchantId', 'merchantPassword', 'acquirerId', 'transactionId');

        return [
            'AcquirerId'  => $this->getAcquirerId(),
            'MerchantId'  => $this->getMerchantId(),
            'Password'    => $this->getMerchantPassword(),
            'OrderNumber' => $this->getTransactionId()
        ];
    }

    /**
     * Returns endpoint for authorize requests
     *
     * @return string Endpoint URL
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . 'TransactionStatus';
    }

    /**
     * Return the transaction modification response object
     *
     * @param \SimpleXMLElement $xml Response xml object
     *
     * @return Response
     */
    protected function newResponse($xml)
    {
        return new Response($this, $xml);
    }
}
