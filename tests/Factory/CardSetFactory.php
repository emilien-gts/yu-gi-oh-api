<?php

namespace App\Tests\Factory;

use App\Entity\CardSet;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class CardSetFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return CardSet::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'name' => self::faker()->unique()->text(255),
        ];
    }
}
