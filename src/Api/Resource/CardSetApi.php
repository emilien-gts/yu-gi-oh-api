<?php

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\CardSet;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'CardSet',
    operations: [
        new Get(),
        new GetCollection(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: CardSet::class)
)]
class CardSetApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;
}
