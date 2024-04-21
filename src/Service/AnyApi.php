<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class AnyApi {
    const RATES_ENDPOINT = 'https://anyapi.io/api/v1/exchange/rates';

    public function __construct(private string $apiKey) {}

    public function getRates(string $base): array
    {
        $client = HttpClient::create();
        $params = [
            'query' => [
                'apiKey' => 13,
                'base'   => $base,
            ]
        ];

        $response = $client->request('GET', self::RATES_ENDPOINT, $params);

        return $response->toArray();
    }
}