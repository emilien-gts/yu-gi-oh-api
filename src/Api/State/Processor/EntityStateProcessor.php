<?php

namespace App\Api\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Validator\Exception\ValidationException;
use Doctrine\Common\Util\ClassUtils;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class EntityStateProcessor extends BaseProcessor
{
    public function __construct(
        #[Autowire(service: PersistProcessor::class)] protected ProcessorInterface $persistProcessor,
        #[Autowire(service: RemoveProcessor::class)] protected ProcessorInterface $removeProcessor,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        $stateOptions = $operation->getStateOptions();
        \assert($stateOptions instanceof Options);

        $entityFqcn = $stateOptions->getEntityClass();
        if ($operation instanceof Patch) {
            $entity = $this->em->find($entityFqcn, $uriVariables['id']);
            if (null === $entity) {
                throw new \RuntimeException('Entity not found');
            }
        }

        $entity = $this->autoMapper->map($data, $entity ?? $entityFqcn);

        if ($operation instanceof DeleteOperationInterface) {
            $this->removeProcessor->process($entity, $operation, $uriVariables, $context);

            return null;
        }

        $violations = $this->validator->validate($entity);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $resourceFqcn = ClassUtils::getClass($data);
        $entity = $this->persistProcessor->process($entity, $operation, $uriVariables, $context);

        return $this->autoMapper->map($entity, $resourceFqcn);
    }
}
