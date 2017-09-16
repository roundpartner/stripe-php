<?php

namespace RoundPartner\Stripe;

class RestClient
{
    /**
     * @var Client
     */
    protected $client;

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
