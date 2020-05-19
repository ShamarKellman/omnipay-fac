<?php

namespace Omnipay\FAC\Message\Response;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\RequestInterface;

/**
 * FACPG2 XML Tokenize Response
 */
class CreateCardResponse extends AbstractResponse
{
    /**
     * CreateCardResponse constructor.
     * @param RequestInterface $request
     * @param mixed $data
     * @throws InvalidResponseException
     */
    public function __construct(RequestInterface $request, $data)
    {
        if ( empty($data) )
        {
            throw new InvalidResponseException();
        }

        $this->request = $request;
        $this->data    = $this->xmlDeserialize($data);
    }

    /**
     * Return whether or not the response was successful
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        return isset($this->data['Success']) && 'true' === $this->data['Success'];
    }

    /**
     * Return the response's reason message
     *
     * @return string
     */
    public function getMessage()
    {
        return isset($this->data['ErrorMsg']) && !empty($this->data['ErrorMsg']) ? $this->data['ErrorMsg'] : null;
    }

    /**
     * Return card reference
     *
     * @return string
     */
    public function getCardReference()
    {
        return isset($this->data['Token']) ? $this->data['Token'] : null;
    }

}
