<?php

namespace Omnipay\FAC;

use Omnipay\Common\AbstractGateway;
use Omnipay\Common\Http\Client;
use Omnipay\Common\Http\ClientInterface;
use Omnipay\Common\Message\AbstractRequest;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\FAC\Message\Request\Authorize3DSRequest;
use Omnipay\FAC\Message\Request\AuthorizeRequest;
use Omnipay\FAC\Message\Request\CancelRecurringRequest;
use Omnipay\FAC\Message\Request\CaptureRequest;
use Omnipay\FAC\Message\Request\CreateCardRequest;
use Omnipay\FAC\Message\Request\FetchHostedTransactionRequest;
use Omnipay\FAC\Message\Request\HostedPurchaseRequest;
use Omnipay\FAC\Message\Request\PurchaseRequest;
use Omnipay\FAC\Message\Request\RefundRequest;
use Omnipay\FAC\Message\Request\StatusRequest;
use Omnipay\FAC\Message\Request\UpdateCardRequest;
use Omnipay\FAC\Message\Request\VoidRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

/**
 * First Atlantic Commerce Payment Gateway 2 (XML POST Service)
 *
 * @method RequestInterface completeAuthorize(array $options = array())
 * @method RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
 */
class Gateway extends AbstractGateway
{
    use ParameterTrait;

    public function __construct(ClientInterface $httpClient = null, HttpRequest $httpRequest = null)
    {
        parent::__construct(new Client(), $httpRequest);
    }

    /**
     * @return string Gateway name.
     */
    public function getName()
    {
        return 'First Atlantic Commerce Payment Gateway 2';
    }

    /**
     * @return array Default parameters.
     */
    public function getDefaultParameters()
    {
        return [
            'merchantId'       => null,
            'merchantPassword' => null,
            'acquirerId'       => '464748',
            'testMode'         => false,
            'requireAvsCheck'  => true
        ];
    }

    public function setMerchantId($value)
    {
        return $this->setParameter('merchantId', $value);
    }

    public function setMerchantPassword($value)
    {
        return $this->setParameter('merchantPassword', $value);
    }

    public function setAcquirerId($value)
    {
        return $this->setAcquirerId($value);
    }

    /**
     * Authorize Request.
     *
     * Authorize an amount on the customer’s card.
     *
     * An Authorize request is similar to a purchase request but the
     * charge issues an authorization (or pre-authorization), and no money
     * is transferred.  The transaction will need to be captured later
     * in order to effect payment. Un-captured charges expire in 7 days.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function authorize(array $parameters = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    /**
     * @param array $parameters
     * @return AbstractRequest
     */
    public function authorize3DS(array $parameters = [])
    {
        return $this->createRequest(Authorize3DSRequest::class, $parameters);
    }

    /**
     * Capture Request.
     *
     * Capture an amount you have previously authorized.
     *
     * Use this request to capture and process a previously created authorization.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function capture(array $parameters = [])
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }

    /**
     *  Purchase request.
     *
     *  To charge a credit card, you create a new charge object.
     *  Authorize and immediately capture an amount on the customer’s card.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function purchase(array $parameters = [])
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    /**
     *  Refund Request.
     *
     * When you create a new refund, you must specify a
     * charge to create it on.
     *
     * Creating a new refund will refund a charge that has
     * previously been created but not yet refunded. Funds will
     * be refunded to the credit or debit card that was originally
     * charged. The fees you were originally charged are also
     * refunded.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function refund(array $parameters = [])
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    /**
     * Fetch Transaction Request.
     *  Reverse an already submitted transaction that hasn't been settled.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function void(array $parameters = [])
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    /**
     *  Retrieve the status of any previous transaction.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function status(array $parameters = [])
    {
        return $this->createRequest(StatusRequest::class, $parameters);
    }

    /**
     *
     *  Create Card.
     *  Create a stored card and return the reference token for future transactions.
     *
     * This call can be used to create a new customer or add a card
     * to an existing customer.  If a customerReference is passed in then
     * a card is added to an existing customer.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function createCard(array $parameters = [])
    {
        return $this->createRequest(CreateCardRequest::class, $parameters);
    }

    /**
     *  Update a stored card.
     *
     * If you need to update only some card details, like the billing
     * address or expiration date, you can do so without having to re-enter
     * the full card details.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function updateCard(array $parameters = [])
    {
        return $this->createRequest(UpdateCardRequest::class, $parameters);
    }

    /**
     * Delete a card.
     *
     * Yu can delete cards from a customer or recipient. If you delete a
     * card that is currently the default card on a customer or recipient,
     * the most recently added card will be used as the new default. If you
     * delete the last remaining card on a customer or recipient, the
     * default_card attribute on the card's owner will become null.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function deleteCard(array $parameters = array())
    {
        //return $this->createRequest(DeleteCardRequest::class, $parameters);
    }

    /**
     *
     * The Cancel Recurring Operation is used to cancel/disable a Recurring transaction.
     * This will prevent the Recurring cycle from executing in the future. This does
     * not refund or reverse an Authorization, but cancels (prevents) any future
     * Recurring payments in a cycle. The Order ID and Amount must match that of
     * the original Recurring Authorization request.
     *
     * @param array $parameters
     * @return AbstractRequest
     */
    public function cancelRecurring(array $parameters = [])
    {
        return $this->createRequest(CancelRecurringRequest::class, $parameters);
    }

    /**
     * @param array $options
     * @return \Omnipay\Common\Message\AbstractRequest
     */
    public function fetchHostedTransaction(array $options = [])
    {
        return $this->createRequest(FetchHostedTransactionRequest::class, $options);
    }

    /**
     *  Authorize and immediately capture an amount on the customer’s card.
     *
     * @param array $parameters
     *
     * @return AbstractRequest
     */
    public function hostedPurchase(array $parameters = [])
    {
        return $this->createRequest(HostedPurchaseRequest::class, $parameters);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\NotificationInterface acceptNotification(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface fetchTransaction(array $options = [])
    }
}
