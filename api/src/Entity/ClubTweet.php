<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *     mercure="true",
 *     attributes={ "pagination_items_per_page"=20, "order"={"createdAt": "DESC"}, "pagination_client_items_per_page"=true, "maximum_items_per_page"=20 },
 *     collectionOperations={ "GET" },
 *     itemOperations={ "GET" }
 * )
 * @ORM\Entity()
 */
class ClubTweet extends Tweet
{}
