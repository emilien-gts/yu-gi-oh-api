<?php

namespace App\Api\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\State\Processor\EntityStateProcessor;
use App\Api\State\Provider\EntityStateProvider;
use App\Api\Transformer\Property\EntityLinkerTransformer;
use App\Api\Transformer\Property\FileTransformer;
use App\Entity\Card;
use App\Enum\Card\CardAttribute;
use App\Enum\Card\CardRarity;
use App\Enum\Card\CardType;
use AutoMapper\Attribute\MapFrom;
use AutoMapper\Attribute\MapTo;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: 'Card',
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
    ],
    provider: EntityStateProvider::class,
    processor: EntityStateProcessor::class,
    stateOptions: new Options(entityClass: Card::class)
)]
class CardApi
{
    #[ApiProperty(writable: false, identifier: true)]
    public ?Uuid $id = null;

    #[Assert\NotBlank]
    public ?string $name = null;

    #[Assert\NotBlank]
    public ?CardRarity $rarity = null;

    #[Assert\NotBlank]
    public ?string $number = null;

    public ?string $otherName = null;

    #[Assert\NotBlank]
    public ?CardType $type = null;

    public ?CardAttribute $attribute = null;

    public ?array $types = null;

    public ?int $level = null;

    public ?int $attack = null;

    public ?int $defense = null;

    #[Assert\NotBlank]
    #[Assert\Length(exactly: 8)]
    public ?string $password = null;

    #[ApiProperty(readableLink: true)]
    #[MapTo(target: Card::class, property: 'set', transformer: EntityLinkerTransformer::class)]
    public ?CardSetApi $set = null;

    #[MapFrom(property: 'imageFilename', transformer: FileTransformer::class)]
    #[ApiProperty(writable: false)] // custom processor will handle this
    public ?string $imageUrl = null;
}
