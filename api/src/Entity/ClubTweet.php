<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     mercure="true",
 *     collectionOperations={ "GET" },
 *     itemOperations={ "GET" }
 * )
 * @ORM\Entity()
 */
class ClubTweet extends Tweet
{}
