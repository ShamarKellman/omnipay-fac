<?php

namespace Omnipay\FAC\Message\Request;

/**
 * FACPG2 Reversal Request
 *
 * Required Parameters:
 * transactionId - Corresponds to the merchant's transaction ID
 * amount - eg. "10.00"
 */
class VoidRequest extends AbstractTransactionModificationRequest
{
    /**
     * Flag as a reversal
     *
     * @var int;
     */
    protected $modificationType = 3;
}
