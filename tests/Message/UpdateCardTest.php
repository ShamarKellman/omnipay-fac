<?php

namespace Omnipay\FAC\Message;;


use Omnipay\FAC\Gateway;
use Omnipay\FAC\Message\Response\UpdateCardResponse;
use Omnipay\Tests\GatewayTestCase;

/**
 * Class UpdateCardTest
 *
 * Tests for the request to update stored cards at FAC
 *
 * @package tests
 */
class UpdateCardTest extends GatewayTestCase
{

    /** @var  Gateway */
    protected $gateway;
    /** @var  array */
    private $updateOptions;

    /**
     * Setup the gateway and the update options for the tests.
     */
    public function setUp(): void
    {
        $this->markTestIncomplete(); //TODO: Fix tests
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->gateway->setMerchantId('123');
        $this->gateway->setMerchantPassword('abc123');

        $this->updateOptions = [
            'customerReference'=>'John Doe',
            'cardReference'=>'411111_000011111',
            'card'=>$this->getValidCard()
        ];
    }

    /**
     * Test a successful update.
     */
    public function testSuccessfulCardUpdate()
    {
        $this->setMockHttpResponse('UpdateCardSuccess.txt');

        /** @var UpdateCardResponse $response */
        $response = $this->gateway->updateCard($this->updateOptions)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertEmpty($response->getMessage());
        $this->assertEquals('411111_000011111', $response->getCardReference());
    }

    /**
     * Test a failed update.
     */
    public function testFailedCardUpdate()
    {
        $this->setMockHttpResponse('UpdateCardFailure.txt');

        /** @var UpdateCardResponse $response */
        $response = $this->gateway->updateCard($this->updateOptions)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('No Data', $response->getMessage());
        $this->assertEmpty($response->getCardReference());
    }
}
