<?php

namespace Test\Provider;

use \GuzzleHttp\Psr7\Response;

class ResponseProvider
{
    /**
     * @return Response[]
     */
    public static function charge()
    {
        return [
            [[new Response(200, [], '{"amount":1000,"amount_refunded":0,"application":null,"captured":true,"created":1505563868,"currency":"gbp","customer":null,"description":"example","destination":null,"dispute":null,"receipt_email":"","receipt_number":"","failure_code":"","failure_message":"","application_fee":null,"fraud_details":{"user_report":"","stripe_report":""},"id":"ch_1B2f8aE6Cs3pyAha1D2mMzTs","invoice":null,"livemode":false,"metadata":{},"outcome":{"network_status":"approved_by_network","reason":"","risk_level":"normal","rule":null,"seller_message":"Payment complete.","type":"authorized"},"paid":true,"refunded":false,"refunds":{"total_count":0,"has_more":false,"url":"/v1/charges/ch_1B2f8aE6Cs3pyAha1D2mMzTs/refunds","data":[]},"review":null,"shipping":null,"source":{"object":"card","customer":null,"id":"card_1B2f8aE6Cs3pyAhaGR3KqMXp","exp_month":8,"exp_year":2019,"fingerprint":"9VS6KW3T2Jjol8av","funding":"credit","last4":"4242","brand":"Visa","currency":"","default_for_currency":false,"address_city":"","address_country":"","address_line1":"","address_line1_check":"","address_line2":"","address_state":"","address_zip":"","address_zip_check":"","country":"US","cvc_check":"","metadata":{},"name":"","recipient":null,"dynamic_last4":"","deleted":false,"three_d_secure":null,"tokenization_method":"","description":"","iin":"","issuer":""},"source_transfer":null,"statement_descriptor":"","status":"succeeded","transfer":null,"transfer_group":"","balance_transaction":{"id":"txn_1B2f8aE6Cs3pyAhagzoGsjp2","amount":0,"currency":"","available_on":0,"created":0,"fee":0,"fee_details":null,"net":0,"status":"","type":"","description":"","source":"","recipient":""}}')]]
        ];
    }

    /**
     * @return Response[]
     */
    public static function chargeFails()
    {
        return [
            [[new Response(400, [], '{"error":{"charge":"ch_1B4lBPE6Cs3pyAhaIvaiLPFR","code":"card_declined","message":"Your card was declined.","request_id":"req_7vc2Sv3BxB5JAC","status":402,"type":"card_error"}}')]]
        ];
    }

    public static function getCustomer()
    {
        return [
            [[new Response(200, [], '{"account_balance":0,"business_vat_id":"","currency":"","created":1506114908,"default_source":null,"deleted":false,"delinquent":false,"description":"First test","discount":null,"email":"test@roundpartner.co.uk","id":"cus_BRsEJtkXRxHxPU","livemode":false,"metadata":{},"shipping":null,"sources":{"total_count":0,"has_more":false,"url":"/v1/customers/cus_BRsEJtkXRxHxPU/sources","data":[]},"subscriptions":{"total_count":0,"has_more":false,"url":"/v1/customers/cus_BRsEJtkXRxHxPU/subscriptions","data":[]}}')]]
        ];
    }

    public static function GetCustomerNotFound()
    {
        return [
            [[new Response(400, [], '{"error":{"message":"No such customer: cus_BRsEJtkXRxHxP","param":"id","request_id":"req_wUNf8y1QwLKc5X","status":404,"type":"invalid_request_error"}}')]]
        ];
    }

    public static function NewCustomer()
    {
        return [
            [[new Response(200, [], '{"account_balance":0,"business_vat_id":"","currency":"","created":1506201248,"default_source":null,"deleted":false,"delinquent":false,"description":"","discount":null,"email":"example@mailinator.com","id":"cus_BSFRjgaYbKzgKD","livemode":false,"metadata":{"account_id":"1"},"shipping":null,"sources":{"total_count":0,"has_more":false,"url":"/v1/customers/cus_BSFRjgaYbKzgKD/sources","data":[]},"subscriptions":{"total_count":0,"has_more":false,"url":"/v1/customers/cus_BSFRjgaYbKzgKD/subscriptions","data":[]}}')]]
        ];
    }
}
