<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\InstagramPost;
use Facebook\Facebook;

class InstagramPostCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    private $facebook;

    public function __construct(Facebook $facebook) {
        $this->facebook = $facebook;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === InstagramPost::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        // https://graph.facebook.com/v3.2/134895793791914?fields=instagram_business_account&access_token={access-token}
//        try {
//            $response = $this->facebook->sendRequest(
//                'GET',
//                '/me/accounts',
//                $params = []
//            );
//            dump($response);
//        } catch (FacebookSDKException $e) {
//            dump($e);
//        }

        // SBIS BUSINESS PAGE ID "442416529153960"

//        try {
//            $response = $this->facebook->sendRequest(
//                'GET',
//                '/442416529153960?fields=instagram_business_account',
//                $params = [
//                    'fields' => 'instagram_business_account'
//                ]
//            );
//            dump($response);
//        } catch (FacebookSDKException $e) {
//            dump($e);
//        }

        // IG Business Account ID "17841400473038209"
        $igUserId = 17841400473038209;

//        try {
//            $response = $this->facebook->sendRequest(
//                'GET',
//                '/ig_hashtag_search',
//                $params = [
//                    'user_id' => $igUserId,
//                    'q' => 'donshub'
//                ]
//            );
//            dump($response->getDecodedBody());
//        } catch (FacebookSDKException $e) {
//            dump($e);
//        }
        // AFCW: 17843727364020062
        // afcwimbledon: 17841563038107451
        // coyd: 17843591884045865
        // donshub: 17886734689202829

//        $hashtags = [
//            'afcw' => 17843727364020062,
//            'afcwimbledon' => 17841563038107451,
//            'coyd' => 17843591884045865,
//            'donshub' => 17886734689202829
//        ];
//        foreach ($hashtags as $hashtag) {
//            try {
//                $response = $this->facebook->sendRequest(
//                    'GET',
//                    '/' . $hashtag . '/recent_media',
//                    $params = [
//                        'user_id' => $igUserId,
//                        'fields' => 'caption,comments_count,id,like_count,media_type,media_url,permalink'
//                    ]
//                );
//                dump($response->getDecodedBody());
//            } catch (FacebookSDKException $e) {
//                dump($e);
//            }
//        }
    }
}
