<?php

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Api\State\Provider\EntityStateProvider;
use App\Api\Transformer\FileTransformer;
use App\Entity\Card;
use App\Enum\Card\CardAttribute;
use App\Enum\Card\CardRarity;
use App\Enum\Card\CardType;
use AutoMapper\Attribute\MapFrom;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Card',
    operations: [
        new Get(),
        new GetCollection(),
    ],
    provider: EntityStateProvider::class,
    stateOptions: new Options(entityClass: Card::class)
)]
class CardApi
{
    #[ApiProperty(identifier: true)]
    public ?Uuid $id = null;

    public ?string $name = null;

    public ?CardRarity $rarity = null;

    public ?string $number = null;

    public ?string $otherName = null;

    public ?CardType $type = null;

    public ?CardAttribute $attribute = null;

    public ?array $types = null;

    public ?int $level = null;

    public ?int $attack = null;

    public ?int $defense = null;

    public ?string $password = null;

    #[ApiProperty(readableLink: true)]
    public ?CardSetApi $set = null;

    #[MapFrom(property: 'imageFilename', transformer: FileTransformer::class)]
    public ?string $imageUrl = null;
}
