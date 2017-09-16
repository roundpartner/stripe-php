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
        $this->assertTrue($response);
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
