<?php

namespace App\Enum\Card;

use App\Model\Labelized;

enum CardType: string implements Labelized
{
    // primary
    case MONSTER = 'monster';
    case SPELL = 'spell';
    case TRAP = 'trap';

    // secondary
    case SPELLCASTER = 'spellcaster';
    case EFFECT = 'effect';
    case THUNDER = 'thunder';
    case ROCK = 'rock';
    case MACHINE = 'machine';
    case SYNCHRO = 'synchro';
    case WARRIOR = 'warrior';
    case XYZ = 'xyz';
    case REPTILE = 'reptile';
    case BEAST = 'beast';
    case TUNER = 'tuner';
    case FAIRY = 'fairy';
    case INSECT = 'insect';
    case FUSION = 'fusion';
    case PLANT = 'plant';
    case DINOSAUR = 'dinosaur';
    case WINGED_BEAST = 'winged beast';
    case AQUA = 'aqua';
    case FIEND = 'fiend';
    case LINK = 'link';
    case PENDULUM = 'pendulum';
    case NORMAL = 'normal';
    case SEA_SERPENT = 'sea serpent';
    case CYBERSE = 'cyberse';
    case BEAST_WARRIOR = 'beast-warrior';
    case FISH = 'fish';
    case GEMINI = 'gemini';
    case DRAGON = 'dragon';
    case ZOMBIE = 'zombie';
    case WYRM = 'wyrm';
    case FLIP = 'flip';
    case PYRO = 'pyro';
    case SPIRIT = 'spirit';
    case RITUAL = 'ritual';
    case PSYCHIC = 'psychic';
    case UNION = 'union';
    case TOON = 'toon';
    case DIVINE_BEAST = 'divine-beast';

    public function getLabel(): string
    {
        return match ($this) {
            self::MONSTER => 'Monster',
            self::SPELL => 'Spell',
            self::TRAP => 'Trap',
            self::SPELLCASTER => 'Spellcaster',
            self::EFFECT => 'Effect',
            self::THUNDER => 'Thunder',
            self::ROCK => 'Rock',
            self::MACHINE => 'Machine',
            self::SYNCHRO => 'Synchro',
            self::WARRIOR => 'Warrior',
            self::XYZ => 'Xyz',
            self::REPTILE => 'Reptile',
            self::BEAST => 'Beast',
            self::TUNER => 'Tuner',
            self::FAIRY => 'Fairy',
            self::INSECT => 'Insect',
            self::FUSION => 'Fusion',
            self::PLANT => 'Plant',
            self::DINOSAUR => 'Dinosaur',
            self::WINGED_BEAST => 'Winged Beast',
            self::AQUA => 'Aqua',
            self::FIEND => 'Fiend',
            self::LINK => 'Link',
            self::PENDULUM => 'Pendulum',
            self::NORMAL => 'Normal',
            self::SEA_SERPENT => 'Sea Serpent',
            self::CYBERSE => 'Cyberse',
            self::BEAST_WARRIOR => 'Beast-Warrior',
            self::FISH => 'Fish',
            self::GEMINI => 'Gemini',
            self::DRAGON => 'Dragon',
            self::ZOMBIE => 'Zombie',
            self::WYRM => 'Wyrm',
            self::FLIP => 'Flip',
            self::PYRO => 'Pyro',
            self::SPIRIT => 'Spirit',
            self::RITUAL => 'Ritual',
            self::PSYCHIC => 'Psychic',
            self::UNION => 'Union',
            self::TOON => 'Toon',
            self::DIVINE_BEAST => 'Divine-Beast',
        };
    }
}
