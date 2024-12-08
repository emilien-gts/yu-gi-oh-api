<?php

namespace App\Api\State\Provider;

use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use AutoMapper\AutoMapperInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Service\Attribute\Required;

abstract class BaseProvider implements ProviderInterface
{
    #[Required]
    public ?AutoMapperInterface $autoMapper = null;

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
