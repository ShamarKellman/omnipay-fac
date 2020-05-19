<?php

namespace Omnipay\FAC;

use Omnipay\FAC\Message\Authorize3DSRequest;
use Omnipay\FAC\Message\AuthorizeRequest;
use Omnipay\FAC\Message\CancelRecurringRequest;
use Omnipay\FAC\Message\CaptureRequest;
use Omnipay\FAC\Message\PurchaseRequest;
use Omnipay\FAC\Message\RefundRequest;
use Omnipay\FAC\Message\Response;
use Omnipay\FAC\Message\VoidRequest;
use Omnipay\Tests\GatewayTestCase;

/**
 * @property Gateway gateway
 */
class GatewayTest extends GatewayTestCase
{
    /** @var  Gateway */
    protected $gateway;

    private $purchaseOptions;
    private $purchaseOptions3DS;

    public function setUp(): void
    {
        parent::setUp();
        $this->markTestIncomplete();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setMerchantId('123');
        $this->gateway->setMerchantPassword('abc123');

        //setup payment details
        $this->purchaseOptions = [
            'amount'        => '10.00',
            'currency'      => 'TTD',
            'transactionId' => '12313',
            'card'          => $this->getValidCard(),
            'testMode'=>true,
            'acquirerId'=>'464748',
        ];

        //setup payment details
        $this->purchaseOptions3DS = [
            'amount'        => '10.00',
            'currency'      => 'TTD',
            'transactionId' => '12316',
            'card'          => $this->getValidCard(),
            'testMode'=>true,
            'acquirerId'=>'464748',
            'eciIndicatorValue'=>'05',
            'cavvValue'=>'jBaKBOUrsH7lCBEAAAAyBzMAAAA=',
            'transactionStain'=>'AgABAwEAAAMBBwIEAgkGCQHsFz8=',
            'authenticationResult'=>'Y'
        ];
    }

    public function testAuthorize()
    {
        /**
         * @var AuthorizeRequest $request
         */
        $request = $this->gateway->authorize($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf(AuthorizeRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1, $response->getReasonCode());
        $this->assertEquals('Transaction is approved.', $response->getMessage());
    }

    public function testAuthorizeThreeDS()
    {
        /**
         * @var Authorize3DSRequest $request
         */
        $request = $this->gateway->authorize3DS($this->purchaseOptions3DS);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf(Authorize3DSRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(0, $response->getReasonCode());
        $this->assertEquals('Success', $response->getMessage());
    }

    public function testCapture()
    {
        /**
         * @var CaptureRequest $request
         */
        $request = $this->gateway->capture($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf(CaptureRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Transaction successful.', $response->getMessage());
    }

    public function testPurchase()
    {
        /**
         * Single pass transaction – authorization and capture as a single transaction
         *
         * @var PurchaseRequest $request
         */
        $request = $this->gateway->purchase($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf(PurchaseRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1, $response->getReasonCode());
        $this->assertEquals('Transaction is approved.', $response->getMessage());
    }

    public function testRefund()
    {
        /**
         * @var RefundRequest $request
         */
        $request = $this->gateway->refund($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf(RefundRequest::class, $request);
        $this->assertSame('10.00', $request->getAmount());

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Transaction successful', $response->getMessage());
    }

    public function testVoid()
    {
        /**
         * NOTE: This the reversal action
         *
         * @var VoidRequest $request
         */
        $request = $this->gateway->void($this->purchaseOptions);
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf(VoidRequest::class, $request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Transaction successful', $response->getMessage());
    }

    public function testCancelRecurring()
    {
        /**
         * NOTE: This the reversal action
         *
         * @var VoidRequest $request
         */
        $request = $this->gateway->void();
        /**
         * @var Response $response
         */
        $response = $request->send();

        $this->assertInstanceOf(CancelRecurringRequest::class, $request);

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Transaction successful', $response->getMessage());
    }

//    public function testCreateCard()
//    {
//        /**
//         * @var CreateCardRequest $request
//         */
//        $request = $this->gateway->createCard(array('description' => 'foo'));
//
//        $this->assertInstanceOf(CreateCardRequest::class, $request);
//        $this->assertSame('foo', $request->getDescription());
//    }

//    public function testUpdateCard()
//    {
//        $request = $this->gateway->updateCard(array('description' => 'foo'));
//
//        $this->assertInstanceOf(UpdateCardRequest::class, $request);
//        $this->assertSame('cus_1MZSEtqSghKx99', $request->getCardReference());
//    }


//    public function testFetchTransaction()
//    {
//        $request = $this->gateway->fetchTransaction(array());
//
//        $this->assertInstanceOf(FetchTransactionRequest::class, $request);
//    }

//    public function testFetchBalanceTransaction()
//    {
//        $request = $this->gateway->fetchBalanceTransaction(array());
//
//        $this->assertInstanceOf(FetchBalanceTransactionRequest::class, $request);
//    }
}
