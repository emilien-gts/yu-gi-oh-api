<?php

namespace App\Api\State\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntityStateProvider extends BaseProvider
{
    public function __construct(
        protected readonly CollectionProvider $collectionProvider,
        protected readonly ItemProvider $itemProvider,
    ) {
    }

    /**
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     *
     * @throws \Exception
     */
    protected function provideCollection(Operation $operation, array $uriVariables = [], array $context = []): TraversablePaginator
    {
        $resourceFqcn = $operation->getClass();
        $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
        \assert($entities instanceof Paginator);

        $resources = [];
        /** @var object $entity */
        foreach ($entities as $entity) {
            $resource = $this->autoMapper->map($entity, $resourceFqcn);
            $resources[] = $resource;
        }

        return new TraversablePaginator(
            new \ArrayIterator($resources),
            $entities->getCurrentPage(),
            $entities->getItemsPerPage(),
            $entities->getTotalItems()
        );
    }

    /**
     * @param array<string, mixed>                                                   $uriVariables
     * @param array<string, mixed>|array{request?: Request, resource_class?: string} $context
     *
     * @throws \Exception
     */
    protected function provideItem(Operation $operation, array $uriVariables = [], array $context = []): object
    {
        $resourceFqcn = $operation->getClass();
        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);
        if (null === $entity) {
            throw new NotFoundHttpException();
        }

        return $this->autoMapper->map($entity, $resourceFqcn);
    }
}
