<?php

namespace App\Tests\Factory;

use App\Entity\Card;
use App\Enum\Card\CardAttribute;
use App\Enum\Card\CardRarity;
use App\Enum\Card\CardType;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class CardFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Card::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'attack' => self::faker()->randomNumber(),
            'attribute' => self::faker()->randomElement(CardAttribute::cases()),
            'defense' => self::faker()->randomNumber(),
            'imageFilename' => self::faker()->text(255),
            'level' => self::faker()->numberBetween(1, 32767),
            'name' => self::faker()->unique()->text(255),
            'number' => self::faker()->text(255),
            'otherName' => self::faker()->text(255),
            'password' => (string) \rand(10000000, 99999999),
            'rarity' => self::faker()->randomElement(CardRarity::cases()),
            'set' => CardSetFactory::new(),
            'type' => self::faker()->randomElement(CardType::cases()),
            'types' => [
                CardType::ROCK->value,
                CardType::DINOSAUR->value,
            ],
        ];
    }
}
