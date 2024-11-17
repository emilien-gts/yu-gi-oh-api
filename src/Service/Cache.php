<?php

namespace App\Service;

use App\Entity\Card;
use App\Entity\CardSet;
use App\Entity\Option;
use App\Enum\OptionCategory;
use App\Repository\CardRepository;
use App\Repository\CardSetRepository;
use App\Repository\OptionRepository;

class Cache
{
    /** @var array<string, mixed> */
    private array $_data = [];

    public function __construct(
        private readonly CardSetRepository $cardSetRepository,
        private readonly CardRepository $cardRepository,
        private readonly OptionRepository $optionRepository,
    ) {
    }

    public function findCardSet(string $name): ?CardSet
    {
        return $this->_data['card_set'][$name] ?? $this->cardSetRepository->findOneBy(['name' => $name]);
    }

    public function findCard(string $name): ?Card
    {
        return $this->_data['card'][$name] ?? $this->cardRepository->findOneBy(['name' => $name]);
    }

    public function findOption(string $label, OptionCategory $category): ?Option
    {
        return $this->_data['option'][$category->value][$label]
            ?? $this->optionRepository->findOneBy(['label' => $label, 'category' => $category]);
    }

    public function register(object $object): void
    {
        if ($object instanceof CardSet) {
            $this->_data['card_set'][$object->getName()] = $object;
        }

        if ($object instanceof Card) {
            $this->_data['card'][$object->getName()] = $object;
        }

        if ($object instanceof Option) {
            $this->_data['option'][$object->getCategory()->value][$object->getLabel()] = $object;
        }
    }

    public function clear(): void
    {
        $this->_data = [];
    }
}
