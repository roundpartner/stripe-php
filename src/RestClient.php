<?php

namespace RoundPartner\Stripe;

use GuzzleHttp\Client;

class RestClient
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * RestClient constructor.
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->client = new Client($options);
    }

    /**
     * @param Client $client
     *
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }
}
