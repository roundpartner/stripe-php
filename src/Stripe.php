<?php

namespace RoundPartner\Stripe;

use GuzzleHttp\Exception\ClientException;
use RoundPartner\Stripe\Exception\CardException;
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
     * @return object[]
     *
     * @throws ClientException
     * @throws \Exception
     */
    public function customers()
    {
        try {
            $response = $this->client->get('/customer');
        } catch (ClientException $exception) {
            throw $exception;
        }
        if (200 !== $response->getStatusCode()) {
            return [];
        }
        return $this->decodeJson($response);
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
     * @param string $user
     * @param string $description
     * @param string $email
     * @param int $discount
     *
     * @throws ClientException
     * @throws \Exception
     *
     * @return object
     */
    public function newCustomer($account, $user, $description, $email, $discount = null)
    {
        $data = [
            'account' => $account,
            'user' => $user,
            'desc' => $description,
            'email' => $email,
            'discount' => $discount,
        ];
        try {
            $response = $this->client->post('/customer', [
                'json' => $data
            ]);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            $json = json_decode($response->getBody());
            if (402 === $json->error->status) {
                throw new CardException($json->error->message, $json->error->status, $exception);
            }
            throw $exception;
        }
        if (200 !== $response->getStatusCode()) {
            return false;
        }
        return $this->decodeJson($response);
    }

    public function updateCustomerCard($id, $token)
    {
        $data = [
            'token' => $token,
        ];
        try {
            $response = $this->client->put('/customer/' . $id . '/card', [
                'json' => $data
            ]);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
            $json = json_decode($response->getBody());
            if (402 === $json->error->status) {
                throw new CardException($json->error->message, $json->error->status, $exception);
            }
            throw $exception;
        }
        if (200 !== $response->getStatusCode()) {
            return false;
        }
        return json_decode($response->getBody());
    }

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return mixed
     *
     * @throws \Exception
     */
    private function decodeJson($response)
    {
        $json = json_decode($response->getBody());
        if (null === $json) {
            throw new \Exception(json_last_error_msg());
        }
        return $json;
    }
}
