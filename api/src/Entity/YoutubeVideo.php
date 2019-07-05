<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={"GET"},
 *     collectionOperations={
 *         "GET"={ "cache_headers"={ "max_age"=30, "shared_max_age"=30 } }
 *     }
 * )
 */
class YoutubeVideo
{
    /**
     * @ApiProperty(identifier=true)
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
