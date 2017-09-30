<?php

namespace RoundPartner\Stripe;

use GuzzleHttp\Exception\ClientException;
use RoundPartner\Stripe\Exception\CustomerNotFoundException;

class Stripe extends RestClient
{
    public function __construct()
    {
        parent::__construct([
            'base_uri' => 'http://0.0.0.0:57493',
        ]);
    }

    /**
     * @param string $token
     * @param int $amount
     * @param string $desc
     * @param string $transId
     * @param string $businessName
     * @param string $customer
     *
     * @return bool
     */
    public function charge($token, $amount, $desc, $transId = null, $businessName = null, $customer = null)
    {
        $data = [
            'trans_id' => $transId,
            'token' => $token,
            'amount' => $amount,
            'desc' => $desc,
            'business_name' => $businessName,
            'customer' => $customer,
        ];
        $response = $this->client->post('/charge', [
            'json' => $data
        ]);
        if (200 !== $response->getStatusCode()) {
            return false;
        }
        return json_decode($response->getBody());
    }

    /**
     * @param string $id
     *
     * @return object
     *
     * @throws CustomerNotFoundException
     */
    public function customer($id)
    {
        try {
            $response = $this->client->get('/customer/' . $id);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            $json = json_decode($response->getBody());
            if (404 === $json->error->status) {
                throw new CustomerNotFoundException($json->error->message, $json->error->status, $exception);
            }
            throw $exception;
        }
        if (200 !== $response->getStatusCode()) {
            return false;
        }
        return json_decode($response->getBody());
    }

    /**
     * @param string $account
     * @param string $description
     * @param string $email
     * @param string $token
     * @param int $discount
     *
     * @return object
     */
    public function newCustomer($account, $description, $email, $token = null, $discount = null)
    {
        $data = [
            'account' => $account,
            'desc' => $description,
            'email' => $email,
            'token' => $token,
            'discount' => $discount,
        ];
        $response = $this->client->post('/customer', [
            'json' => $data
        ]);
        if (200 !== $response->getStatusCode()) {
            return false;
        }
        return json_decode($response->getBody());
    }
}
