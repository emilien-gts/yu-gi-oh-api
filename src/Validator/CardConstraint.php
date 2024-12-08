<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class CardConstraint extends Constraint
{
    public const string UNIQUENESS_NAME_SET_MESSAGE = 'This card already exists in set';
    public const string UNIQUENESS_PASSWORD_MESSAGE = 'This card already exists with this password';
    public const string UNIQUENESS_NUMBER_MESSAGE = 'This card already exists with this number';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy(): string
    {
        return CardValidator::class;
    }
}
