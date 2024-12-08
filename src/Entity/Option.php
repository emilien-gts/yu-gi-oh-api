<?php

namespace App\Entity;

use App\Enum\OptionCategory;
use App\Model\IdTrait;
use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
class Option
{
    use IdTrait;

    #[ORM\Column(length: 50)]
    private ?OptionCategory $category = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    public function getCategory(): ?OptionCategory
    {
        return $this->category;
    }

    public function setCategory(?OptionCategory $category): void
    {
        $this->category = $category;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }
}
