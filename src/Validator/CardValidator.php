<?php

namespace App\Validator;

use App\Entity\Card;
use App\Repository\CardRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CardValidator extends ConstraintValidator
{
    public function __construct(private readonly CardRepository $repository)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof Card) {
            throw new UnexpectedTypeException($value, Card::class);
        }

        $this->validateIsUnique($value);
    }

    private function validateIsUnique(Card $card): void
    {
        if (!$this->repository->isUnique($card, CardRepository::UNIQUENESS_NAME_SET)) {
            $this->context->buildViolation(CardConstraint::UNIQUENESS_NAME_SET_MESSAGE)
                ->atPath('name')
                ->addViolation();

            return;
        }

        if (!$this->repository->isUnique($card, CardRepository::UNIQUENESS_PASSWORD)) {
            $this->context->buildViolation(CardConstraint::UNIQUENESS_PASSWORD_MESSAGE)
                ->atPath('password')
                ->addViolation();

            return;
        }

        if (!$this->repository->isUnique($card, CardRepository::UNIQUENESS_NUMBER)) {
            $this->context->buildViolation(CardConstraint::UNIQUENESS_NUMBER_MESSAGE)
                ->atPath('number')
                ->addViolation();
        }
    }
}
