<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\InstagramPost;

class InstagramPostCollectionDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct() {
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === InstagramPost::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
    }
}
