<?php

namespace App\DataProvider;

use Abraham\TwitterOAuth\TwitterOAuth;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\Tweet;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

final class TweetItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
//    private $twitterOAuth;
//    private $denormalizer;
//
//    public function __construct(TwitterOAuth $twitterOAuth, DenormalizerInterface $denormalizer)
//    {
//        $this->twitterOAuth = $twitterOAuth;
//        $this->denormalizer = $denormalizer;
//    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return Tweet::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Tweet
    {
        return null;
        // We could return tweets by IDs but it would leave us open to abuse without authentication
//        $response = $this->twitterOAuth->get('search/tweets', [
//            'q' => $id
//        ]);
//        $statuses = json_decode(json_encode($response->statuses), true);
//        if (!count($statuses)) {
//            return null;
//        }
//        /** @var Tweet $tweet */
//        $tweet = $this->denormalizer->denormalize($statuses[0], Tweet::class);
//        return $tweet;
    }
}
