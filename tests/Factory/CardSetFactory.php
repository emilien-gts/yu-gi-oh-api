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

    /**
     * @return array<string, mixed>
     */
    protected function defaults(): array
    {
        return [
            'name' => self::faker()->unique()->text(255),
        ];
    }
}
