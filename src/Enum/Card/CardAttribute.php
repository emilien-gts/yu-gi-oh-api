<?php

namespace App\Enum\Card;

use App\Model\Labelized;

enum CardAttribute: string implements Labelized
{
    case DARK = 'dark';
    case DIVINE = 'divine';
    case EARTH = 'earth';
    case FIRE = 'fire';
    case LIGHT = 'light';
    case WATER = 'water';
    case WIND = 'wind';

    public function getLabel(): string
    {
        return match ($this) {
            self::DARK => 'Dark',
            self::DIVINE => 'Divine',
            self::EARTH => 'Earth',
            self::FIRE => 'Fire',
            self::LIGHT => 'Light',
            self::WATER => 'Water',
            self::WIND => 'Wind',
        };
    }
}
