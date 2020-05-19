<?php


namespace Omnipay\FAC\Message\Response;


class Authorize3DSResponse extends AuthorizeResponse
{
    /**
     * Return transaction reference
     *
     * @return string
     */
    public function getHtmlFormData()
    {
        return isset($this->data['HTMLFormData']) ? $this->data['HTMLFormData'] : null;
    }
}
