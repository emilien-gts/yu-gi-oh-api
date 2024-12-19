<?php

namespace App\Api\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
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
        #[Autowire(service: PersistProcessor::class)] private readonly ProcessorInterface $persistProcessor,
        #[Autowire(service: RemoveProcessor::class)] private readonly ProcessorInterface $removeProcessor,
        private readonly ItemProvider $itemProvider,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ?object
    {
        $stateOptions = $operation->getStateOptions();
        \assert($stateOptions instanceof Options);

        $entityFqcn = $stateOptions->getEntityClass();

        if ($operation instanceof DeleteOperationInterface) {
            $entity = $this->itemProvider->provide($operation, $uriVariables, $context);
            $this->removeProcessor->process($entity, $operation, $uriVariables, $context);

            return null;
        }

        if ($operation instanceof Patch) {
            $entity = $this->itemProvider->provide($operation, $uriVariables, $context);
        }

        $entity = $this->autoMapper->map($data, $entity ?? $entityFqcn);

        $violations = $this->validator->validate($entity);
        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }

        $resourceFqcn = ClassUtils::getClass($data);
        $entity = $this->persistProcessor->process($entity, $operation, $uriVariables, $context);

        return $this->autoMapper->map($entity, $resourceFqcn);
    }
}
