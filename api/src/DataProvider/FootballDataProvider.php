<?php

namespace App\DataProvider;

use App\Entity\Match;
use App\Exception\FootballApiException;
use DateTime;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FootballDataProvider
{
    private $httpClient;
    private $baseUrl = 'https://apiv2.apifootball.com/';
    public static $teamId = 2664; // AFC Wimbledon Team ID
    private $apiKey;
    private static $dateFormat = 'Y-m-d';

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

    /**
     * @param Match $match
     * @return array
     * @throws ClientExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchLeagueEvents(Match $match): array
    {
        $matchDateTime = $match->getMatchDateTime();
        if (!$matchDateTime) {
            throw new FootballApiException('Match date time does not exist');
        }
        $response = $this->request('get_events', [
            'from' => $matchDateTime->setTime(0,0)->format(self::$dateFormat),
            'to' => $matchDateTime->setTime(23,59, 59)->format(self::$dateFormat),
            'league_id' => $match->getLeagueId()
        ]);
        return $this->handleResponse($response);
    }

    /**
     * @param Match $match
     * @return array
     * @throws ClientExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchLeagueByMatch(Match $match): array
    {
        $response = $this->request('get_standings', [
            'league_id' => $match->getLeagueId()
        ]);
        return $this->handleResponse($response);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function fetchUpcomingEvents(): array
    {
        $fromDate = new DateTime('');
        $toDate = (new DateTime())->modify('+6 months');
        $response = $this->request('get_events', [
            'from' => $fromDate->format(self::$dateFormat),
            'to' => $toDate->format(self::$dateFormat),
            'team_id' => self::$teamId
        ]);
        return $this->handleResponse($response);
    }

    /**
     * @param ResponseInterface $response
     * @return array
     * @throws ClientExceptionInterface
     * @throws FootballApiException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function handleResponse(ResponseInterface $response): array
    {
        if ($response->getStatusCode() !== 200) {
            throw new FootballApiException('Error: Status code: ' . $response->getStatusCode());
        }
        $content = $response->getContent();

        $contentArray = json_decode($content, true);
        if (array_key_exists('error', $contentArray)) {
            throw new FootballApiException(sprintf('%s: %s', $contentArray['error'], $contentArray['message']));
        }
        return $contentArray;
    }
}
