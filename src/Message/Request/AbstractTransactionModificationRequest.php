<?php

namespace Omnipay\FAC\Message\Request;

use Omnipay\Common\Message\ResponseInterface;
use Omnipay\FAC\Message\Response\Response;

/**
 * FACPG2 Transaction Modification Request
 */
abstract class AbstractTransactionModificationRequest extends AbstractRequest
{
    /**
     * @var string;
     */
    protected $requestName = 'TransactionModificationRequest';

    /**
     * Modification Type
     *
     * @var int;
     */
    protected $modificationType;

    /**
     * Validate and construct the data for the request
     *
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     */
    public function getData()
    {
        $this->validate('merchantId', 'merchantPassword', 'acquirerId', 'transactionId', 'amount');

        return [
            'AcquirerId'       => $this->getAcquirerId(),
            'Amount'           => $this->formatAmount(),
            'CurrencyExponent' => $this->getCurrencyDecimalPlaces(),
            'MerchantId'       => $this->getMerchantId(),
            'ModificationType' => $this->getModificationType(),
            'OrderNumber'      => $this->getTransactionId(),
            'Password'         => $this->getMerchantPassword()
        ];
    }

    /**
     * Returns endpoint for authorize requests
     *
     * @return string Endpoint URL
     */
    public function getEndpoint()
    {
        return parent::getEndpoint() . 'TransactionModification';
    }

    /**
     * Returns the modification type
     *
     * @return int Modification Type
     */
    protected function getModificationType()
    {
        return $this->modificationType;
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
