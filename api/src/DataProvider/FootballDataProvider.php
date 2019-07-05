<?php

namespace App\DataProvider;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FootballDataProvider
{
    private $httpClient;
    private $baseUrl = 'https://apiv2.apifootball.com/';
    public static $teamId = 2664; // AFC Wimbledon Team ID
    private $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->httpClient = HttpClient::create();
    }

    /**
     * @param string $action
     * @param array $data
     * @return ResponseInterface
     * @throws TransportExceptionInterface
     */
    public function request(string $action, array $data = []): ResponseInterface
    {
        $querystring = http_build_query(array_merge($data, [
            'APIkey' => $this->apiKey,
            'action' => $action
        ]));
        return $this->httpClient->request('GET', sprintf('%s?%s', $this->baseUrl, $querystring));
    }
}
