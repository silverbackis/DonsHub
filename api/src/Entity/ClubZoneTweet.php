<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={"GET"},
 *     collectionOperations={
 *         "GET"={ "cache_headers"={ "max_age"=10, "shared_max_age"=10 } }
 *     }
 * )
 */
class ClubZoneTweet extends Tweet
{}
