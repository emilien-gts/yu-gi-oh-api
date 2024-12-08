<?php

namespace App\Api\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use AutoMapper\AutoMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class BaseProcessor implements ProcessorInterface
{
    #[Required]
    public ?EntityManagerInterface $em = null;

    #[Required]
    public ?AutoMapperInterface $autoMapper = null;

    #[Required]
    public ?ValidatorInterface $validator = null;

    /**     $uriVariables.
     * @param array<string, mixed>&array{request?: Request, previous_data?: mixed, resource_class?: string|null, original_data?: mixed} $context
     */
    abstract public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []);
}
