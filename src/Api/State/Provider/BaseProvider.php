<?php

namespace App\Api\State\Provider;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\Api\Service\ApiTransformerService;
use Symfony\Component\HttpFoundation\Request;

abstract class BaseProvider implements ProviderInterface
{
    public function __construct(
        protected readonly ApiTransformerService $transformerService,
        protected readonly CollectionProvider $collectionProvider,
        protected readonly ItemProvider $itemProvider,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if ($operation instanceof CollectionOperationInterface) {
            return $this->provideCollection($operation, $uriVariables, $context);
        }

        return $this->provideItem($operation, $uriVariables, $context);
    }

    /**
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     */
    abstract protected function provideCollection(Operation $operation, array $uriVariables = [], array $context = []): TraversablePaginator;

    /**
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     */
    abstract protected function provideItem(Operation $operation, array $uriVariables = [], array $context = []): object;
}
