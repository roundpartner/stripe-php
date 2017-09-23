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
     * @dataProvider \Test\Provider\ResponseProvider::GetCustomerNotFound()
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
     * @dataProvider \Test\Provider\ResponseProvider::NewCustomer
     */
    public function testNewCustomer($responses)
    {
        $client = $this->getClientMock($responses);
        $this->instance->setClient($client);
        $response = $this->instance->newCustomer("1", "Test Payment", "example@mailinator.com");
        $this->assertInternalType('object', $response);
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
