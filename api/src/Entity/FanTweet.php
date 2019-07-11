<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     mercure="true",
 *     attributes={ "pagination_items_per_page"=20, "order"={"createdAt": "DESC"} },
 *     collectionOperations={ "GET" },
 *     itemOperations={ "GET" }
 * )
 * @ORM\Entity()
 */
class FanTweet extends Tweet
{}
