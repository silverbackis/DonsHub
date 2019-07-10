<?php

namespace App\DataProvider;

use Abraham\TwitterOAuth\TwitterOAuth;

class TweetsDataProvider
{
    private $twitterOAuth;

    public function __construct(TwitterOAuth $twitterOAuth)
    {
        $this->twitterOAuth = $twitterOAuth;
    }

    /**
     * @param string $query
     * @param int $count
     * @return array|object
     */
    private function request(string $query, int $count = 50)
    {
        $response = $this->twitterOAuth->get('search/tweets', [
            'q' => $query,
            'count' => $count
        ]);
        return json_decode(json_encode($response->statuses), true);
    }

    /**
     * @return array
     */
    public function fetchClubTweets(): array
    {
        return $this->request('from:AFCWimbledon');
    }

    /**
     * @return array
     */
    public function fetchFanTweets(): array
    {
        return $this->request('#COYD OR #AFCW OR #AFCWimbledon OR #DonsHub min_faves:2 +@AFCWimbledon');
    }
}
