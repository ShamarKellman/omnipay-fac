<?php

namespace Omnipay\FAC\Message\Request;


use Omnipay\FAC\Message\Response\CreateCardResponse;

/**
 * FACPG2 Tokenize Request
 *
 * Required Parameters:
 * customerReference - name of the customer using the card
 * card - Instantiation of Omnipay\FAC\CreditCard()
 *
 */
class CreateCardRequest extends AbstractRequest
{
    /**
     * @var string;
     */
    protected $requestName = 'TokenizeRequest';

    /**
     * Returns the signature for the request.
     *
     * @return string base64 encoded sha1 hash of the merchantPassword, merchantId,
     *    and acquirerId.
     */
    protected function generateSignature()
    {
        $signature  = $this->getMerchantPassword();
        $signature .= $this->getMerchantId();
        $signature .= $this->getAcquirerId();

        return base64_encode( sha1($signature, true) );
    }

    /**
     * Validate and construct the data for the request
     *
     * @return array
     * @throws \Omnipay\Common\Exception\InvalidRequestException
     * @throws \Omnipay\Common\Exception\InvalidCreditCardException
     */
    public function getData()
    {
        $this->validate('merchantId', 'merchantPassword', 'acquirerId', 'customerReference', 'card');
        $this->getCard()->validate();

        return [
            'CardNumber'        => $this->getCard()->getNumber(),
            'CustomerReference' => $this->getCustomerReference(),
            'ExpiryDate'        => $this->getCard()->getExpiryDate('my'),
            'MerchantNumber'    => $this->getMerchantId(),
            'Signature'         => $this->generateSignature()
        ];
    }

    /**
     * Get the customer reference.
     *
     * @return string
     */
    public function getCustomerReference()
    {
        return $this->getParameter('customerReference');
    }

    /**
     * Set the customer reference.
     *
     * @param $value
     * @return string $value
     */
    public function setCustomerReference($value)
    {
        return $this->setParameter('customerReference', $value);
    }

    /**
     * Returns endpoint for tokenize requests
     *
     * @return string Endpoint URL
     */
    protected function getEndpoint()
    {
        return parent::getEndpoint() . 'Tokenize';
    }

    /**
     * Return the tokenize response object
     *
     * @param  \SimpleXMLElement  $xml  Response xml object
     *
     * @return CreateCardResponse
     * @throws \Omnipay\Common\Exception\InvalidResponseException
     */
    protected function newResponse($xml)
    {
        return new CreateCardResponse($this, $xml);
    }

}
