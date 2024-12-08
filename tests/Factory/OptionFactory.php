<?php

namespace App\Tests\Factory;

use App\Entity\Option;
use App\Enum\OptionCategory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

final class OptionFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Option::class;
    }

    protected function defaults(): array|callable
    {
        return [
            'category' => self::faker()->randomElement(OptionCategory::cases()),
            'label' => self::faker()->text(255),
        ];
    }
}
