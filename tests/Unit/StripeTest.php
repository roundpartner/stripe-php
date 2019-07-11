<?php

namespace Test\Integration;

use RoundPartner\Stripe\Stripe;

use \PHPUnit\Framework\TestCase;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class StripeTest extends TestCase
{
    /**
     * @var Stripe
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new Stripe();
    }

    public function testInit()
    {
        $this->assertInstanceOf('\RoundPartner\Stripe\Stripe', $this->instance);
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::charge()
     */
    public function testPurchase($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $response = $this->instance->charge("tok_visa", 1000, "Test Payment");
        $this->assertInternalType('object', $response);
    }

    /**
     * @param Response[] $responses
     *
     * @expectedException \GuzzleHttp\Exception\ClientException
     *
     * @dataProvider \Test\Provider\ResponseProvider::chargeFails()
     */
    public function testPurchaseFailed($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $this->instance->charge("tok_visa", 1000, "Test Payment");
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::getCustomers()
     */
    public function testCustomers($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $response = $this->instance->customers();
        $this->assertCount(1, $response);
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::getCustomer()
     */
    public function testCustomer($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $response = $this->instance->customer('cus_12345');
        $this->assertInternalType('object', $response);
    }

    /**
     * @param Response[] $responses
     *
     * @expectedException \RoundPartner\Stripe\Exception\CustomerNotFoundException
     *
     * @dataProvider \Test\Provider\ResponseProvider::getCustomerNotFound()
     */
    public function testCustomerNotFound($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $this->instance->customer('cus_12345');
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::newCustomer
     */
    public function testNewCustomer($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $response = $this->instance->newCustomer("1", "2", "Test Payment", "example@mailinator.com");
        $this->assertInternalType('object', $response);
    }

    /**
     * @param Response[] $responses
     *
     * @expectedException \Exception
     *
     * @dataProvider \Test\Provider\ResponseProvider::badJsonResponse()
     */
    public function testNewCustomerReturnsBadJson($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $this->instance->newCustomer("1", "2", "Test Payment", "example@mailinator.com");
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::updateCard()
     */
    public function testUpdateCustomerCard($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $response = $this->instance->updateCustomerCard('cus_12345', 'tok_visa');
        $this->assertInternalType('object', $response);
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::getCustomer()
     *
     * @throws \Exception
     */
    public function testUpdateCustomerEmail($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $response = $this->instance->updateCustomerEmail('cus_12345', 'test@mailinator.com');
        $this->assertInternalType('object', $response);
    }

    /**
     * @param Response[] $responses
     *
     * @dataProvider \Test\Provider\ResponseProvider::getCustomerSubscription()
     */
    public function testCustomerSubscription($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $response = $this->instance->customerSubscriptions('cus_12345');
        $this->assertCount(1, $response);
    }

    /**
     * @param Response[] $responses
     *
     * @return Client
     */
    protected function getClientMock($responses)
    {
        $mock = new MockHandler($responses);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        return $client;
    }
}
