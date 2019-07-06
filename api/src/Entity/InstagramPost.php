<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     itemOperations={"GET"},
 *     collectionOperations={
 *         "GET"={ "cache_headers"={ "max_age"=10, "shared_max_age"=10 } }
 *     }
 * )
 */
class InstagramPost
{
    /**
     * @ApiProperty(identifier=true)
     * @var int
     */
    private $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
