<?php

namespace App\Enum\Card;

use App\Model\Labelized;

enum CardRarity: string implements Labelized
{
    case COMMON = 'common';
    case GHOST_RARE = 'ghost_rare';
    case PREMIUM_GOLD_RARE = 'premium_gold_rare';
    case RARE = 'rare';
    case SECRET_RARE = 'secret_rare';
    case SHORT_PRINT = 'short_print';
    case STARLIGHT_RARE = 'starlight_rare';
    case SUPER_RARE = 'super_rare';
    case SUPER_SHORT_PRINT = 'super_short_print';
    case ULTRA_RARE = 'ultra_rare';

    public function getLabel(): string
    {
        return match ($this) {
            self::COMMON => 'Common',
            self::GHOST_RARE => 'Ghost Rare',
            self::PREMIUM_GOLD_RARE => 'Premium Gold Rare',
            self::RARE => 'Rare',
            self::SECRET_RARE => 'Secret Rare',
            self::SHORT_PRINT => 'Short Print',
            self::STARLIGHT_RARE => 'Starlight Rare',
            self::SUPER_RARE => 'Super Rare',
            self::SUPER_SHORT_PRINT => 'Super Short Print',
            self::ULTRA_RARE => 'Ultra Rare',
        };
    }
}
