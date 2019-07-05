<?php

namespace App\DataProvider;

use Abraham\TwitterOAuth\TwitterOAuth;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SerializerAwareDataProviderInterface;
use ApiPlatform\Core\DataProvider\SerializerAwareDataProviderTrait;
use App\Entity\FanZoneTweet;
use App\Entity\Tweet;
use Generator;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Traversable;

class TweetCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface, SerializerAwareDataProviderInterface
{
    use SerializerAwareDataProviderTrait;

    private $twitterOAuth;
    private $denormalizer;

    public function __construct(TwitterOAuth $twitterOAuth, DenormalizerInterface $denormalizer)
    {
        $this->twitterOAuth = $twitterOAuth;
        $this->denormalizer = $denormalizer;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return is_subclass_of($resourceClass, Tweet::class);
    }

    /**
     * @param string $resourceClass
     * @param string|null $operationName
     * @return array|Generator|Traversable
     * @throws ExceptionInterface
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $response = $this->twitterOAuth->get('search/tweets', [
            'q' => $resourceClass === FanZoneTweet::class ?
                '#COYD OR #AFCW OR #AFCWimbledon OR #DonsHub min_faves:2 +@AFCWimbledon' :
                'from:AFCWimbledon',
            'count' => 20
        ]);
        $statuses = json_decode(json_encode($response->statuses), true);
        yield from $this->denormalizer->denormalize($statuses, $resourceClass . '[]');
    }
}
