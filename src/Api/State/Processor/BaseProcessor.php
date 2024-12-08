<?php

namespace App\Api\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Service\ApiTransformerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseProcessor implements ProcessorInterface
{
    public function __construct(
        protected EntityManagerInterface $em,
        protected ApiTransformerService $transformerService,
        protected ValidatorInterface $validator,
    ) {
    }

    abstract public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []);
}
