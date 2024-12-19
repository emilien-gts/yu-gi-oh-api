<?php

namespace App\Entity;

use App\Enum\Card\CardAttribute;
use App\Enum\Card\CardRarity;
use App\Enum\Card\CardType;
use App\Model\IdTrait;
use App\Repository\CardRepository;
use App\Validator\CardConstraint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[CardConstraint]
class Card
{
    use IdTrait;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?CardRarity $rarity = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $number = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $otherName = null;

    #[ORM\Column(length: 50)]
    private ?CardType $type = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?CardAttribute $attribute = null;

    /**
     * @var array<CardType>|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $types = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $level = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $attack = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $defense = null;

    #[ORM\Column(length: 8, unique: true)]
    private ?string $password = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'cards')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?CardSet $set = null;

    /**
     * @var Collection<int, Option>
     */
    #[ORM\ManyToMany(targetEntity: Option::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(onDelete: 'CASCADE')]
    #[ORM\JoinTable(name: 'card_status')]
    private Collection $statuses;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageFilename = null;

    public function __construct()
    {
        $this->statuses = new ArrayCollection();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getRarity(): ?CardRarity
    {
        return $this->rarity;
    }

    public function setRarity(?CardRarity $rarity): void
    {
        $this->rarity = $rarity;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): void
    {
        $this->number = $number;
    }

    public function getOtherName(): ?string
    {
        return $this->otherName;
    }

    public function setOtherName(?string $otherName): void
    {
        $this->otherName = $otherName;
    }

    public function getType(): ?CardType
    {
        return $this->type;
    }

    public function setType(?CardType $type): void
    {
        $this->type = $type;
    }

    public function getAttribute(): ?CardAttribute
    {
        return $this->attribute;
    }

    public function setAttribute(?CardAttribute $attribute): void
    {
        $this->attribute = $attribute;
    }

    /**
     * @return array<CardType>
     */
    public function getTypes(): array
    {
        if (null === $this->types) {
            return [];
        }

        $types = [];
        /** @var string $type */
        foreach ($this->types as $type) {
            $case = CardType::tryFrom($type);
            if ($case instanceof CardType) {
                $types[] = $case;
            }
        }

        return $types;
    }

    /**
     * @param CardType[] $types
     */
    public function setTypes(array $types): void
    {
        $this->types = $types;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): void
    {
        $this->level = $level;
    }

    public function getAttack(): ?int
    {
        return $this->attack;
    }

    public function setAttack(?int $attack): void
    {
        $this->attack = $attack;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(?int $defense): void
    {
        $this->defense = $defense;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    public function getSet(): ?CardSet
    {
        return $this->set;
    }

    public function setSet(?CardSet $set): static
    {
        $this->set = $set;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(Option $status): static
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses->add($status);
        }

        return $this;
    }

    public function removeStatus(Option $status): static
    {
        $this->statuses->removeElement($status);

        return $this;
    }

    public function getImageFilename(): ?string
    {
        return $this->imageFilename;
    }

    public function setImageFilename(string $imageFilename): static
    {
        $this->imageFilename = $imageFilename;

        return $this;
    }
}
