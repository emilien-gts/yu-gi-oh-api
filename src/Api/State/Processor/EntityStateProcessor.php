<?php

namespace App\Api\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\Exception\ValidationException;
use App\Api\Service\ApiTransformerService;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EntityStateProcessor extends BaseProcessor
{
    public function __construct(
        EntityManagerInterface $em,
        ApiTransformerService $transformerService,
        ValidatorInterface $validator,
        #[Autowire(service: PersistProcessor::class)] protected ProcessorInterface $persistProcessor,
        #[Autowire(service: RemoveProcessor::class)] protected ProcessorInterface $removeProcessor,
    ) {
        parent::__construct($em, $transformerService, $validator);
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        $stateOptions = $operation->getStateOptions();
        \assert($stateOptions instanceof Options);

        $entityFqcn = $stateOptions->getEntityClass();
        $entity = $this->transformerService->transform($data, $entityFqcn);

        if ($operation instanceof DeleteOperationInterface) {
            return $this->removeProcessor->process($entity, $operation, $uriVariables, $context);
        }

        $violations = $this->validator->validate($entity);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $resourceFqcn = ClassUtils::getClass($data);
        $entity = $this->persistProcessor->process($entity, $operation, $uriVariables, $context);

        return $this->transformerService->transform($entity, $resourceFqcn);
    }
}
