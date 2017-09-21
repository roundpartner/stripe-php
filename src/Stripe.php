<?php

namespace RoundPartner\Stripe;

use GuzzleHttp\Client;

class Stripe extends RestClient
{
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://0.0.0.0:57493',
        ]);
    }

    /**
     * @param string $token
     * @param int $amount
     * @param string $desc
     * @param string $transId
     * @param string $businessName
     *
     * @return bool
     */
    public function charge($token, $amount, $desc, $transId = null, $businessName = null)
    {
        $data = [
            'trans_id' => $transId,
            'token' => $token,
            'amount' => $amount,
            'desc' => $desc,
            'business_name' => $businessName,
        ];
        $response = $this->client->post('/charge', [
            'body' => json_encode($data)
        ]);
        if (200 !== $response->getStatusCode()) {
            return false;
        }
        return json_decode($response->getBody());
    }
}
