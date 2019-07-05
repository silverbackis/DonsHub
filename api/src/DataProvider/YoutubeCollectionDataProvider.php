<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\YoutubeVideo;
use Google_Service_YouTube;
use Google_Service_YouTube_SearchResult;

class YoutubeCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $googleClient;

    public function __construct(\Google_Client $googleClient) {
        $this->googleClient = $googleClient;
        $this->googleClient->setScopes([
            'https://www.googleapis.com/auth/youtube.force-ssl',
        ]);
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === YoutubeVideo::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $service = new Google_Service_YouTube($this->googleClient);

        $queryParams = [
            'channelId' => 'UCYVQL8cG2bwoH9wx35if0Gw',
            'maxResults' => 10,
            'q' => 'fun',
            'type' => 'video'
        ];

        $response = $service->search->listSearch('snippet', $queryParams);
        $items = $response->getItems();
        /** @var Google_Service_YouTube_SearchResult $item */
        foreach ($items as $item) {
            yield new YoutubeVideo($item->getId()->getVideoId());
        }
    }
}
