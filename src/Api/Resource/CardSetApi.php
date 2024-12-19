<?php

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Api\State\Processor\EntityStateProcessor;
use App\Api\State\Provider\EntityStateProvider;
use App\Entity\CardSet;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'CardSet',
    operations: [
        new Get(uriTemplate: self::ITEM_URI_TEMPLATE),
        new GetCollection(uriTemplate: self::URI_TEMPLATE, itemUriTemplate: self::ITEM_URI_TEMPLATE),
        new Post(uriTemplate: self::URI_TEMPLATE),
        new Patch(uriTemplate: self::ITEM_URI_TEMPLATE),
        new Delete(uriTemplate: self::ITEM_URI_TEMPLATE),
    ],
    provider: EntityStateProvider::class,
    processor: EntityStateProcessor::class,
    stateOptions: new Options(entityClass: CardSet::class),
)]
class CardSetApi
{
    private const string URI_TEMPLATE = '/sets';
    private const string ITEM_URI_TEMPLATE = self::URI_TEMPLATE.'/{id}';

    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;
}
