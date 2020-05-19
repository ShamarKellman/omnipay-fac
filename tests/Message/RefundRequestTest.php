<?php

namespace Omnipay\FAC\Message;

use Omnipay\FAC\Gateway;
use Omnipay\FAC\Message\Request\CaptureRequest;
use Omnipay\FAC\Message\Request\RefundRequest;
use Omnipay\FAC\Message\Response\Response;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /** @var  Gateway */
    protected $gateway;

    private $purchaseOptions;

    /**
     * @var CaptureRequest
     */
    private $request;


    public function setUp(): void
    {
        $this->markTestIncomplete(); //TODO: Fix tests
        $this->request = new RefundRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'amount' => '10.00',
                'currency' => 'TTD',
                'transactionId' => '1234',
                'merchantId'=>123,
                'merchantPassword'=>'abc123',
                'acquirerId'=>'464748',
                'testMode'=>true
            )
        );

        //set up gateway
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
//        $this->gateway->setMerchantId('123');
//        $this->gateway->setMerchantPassword('abc123');

        $this->gateway->setMerchantId('88800993');
        $this->gateway->setMerchantPassword('9EaI1fCZ');

        //setup payment details
        $this->purchaseOptions = [
            'amount'        => '10.00',
            'currency'      => 'TTD',
            'transactionId' => '1237',
            'currencyExponent'=>'2',
            'card'          => $this->getValidCard(),
            'testMode'=>true
        ];
    }

    public function testEndpoint()
    {
        //test mode set to true
        $this->assertSame('https://ecm.firstatlanticcommerce.com/PGServiceXML/TransactionModification', $this->request->getEndpoint());
    }

    public function testAmount()
    {
        $data = $this->request->getData();
        $this->assertSame(1000, (int)$data['Amount']);
    }

    /**
     * Test a successful refund.
     */
    public function testSendSuccess()
    {
        $this->setMockHttpResponse('ModificationSuccess.txt');

        /** @var Response $response */
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getReasonCode());
        $this->assertEquals('Success', $response->getMessage());
    }

    /**
     * Test a failed refund.
     */
    public function testSendFailed()
    {
        $this->setMockHttpResponse('ModificationFailed.txt');

        /** @var Response $response */
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(1100, $response->getReasonCode());
        $this->assertEquals('Failed', $response->getMessage());
    }

    /**
     * Test a successful refund.
     */
    public function testSuccessfulRefund()
    {
        /** @var Response $response */
        $response = $this->gateway->refund($this->purchaseOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEquals(1101, $response->getCode());
        $this->assertEquals('Success', $response->getMessage());
    }

    /**
     * Test a failed refund.
     */
    public function testFailedRefund()
    {
        /** @var Response $response */
        $response = $this->gateway->refund($this->purchaseOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(1100, $response->getReasonCode());
        $this->assertEquals('Failed', $response->getMessage());
    }

}
