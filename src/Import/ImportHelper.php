<?php

namespace App\Import;

use Doctrine\DBAL\Logging\Middleware;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\NullLogger;

class ImportHelper
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function disableSQLLog(): void
    {
        $middlewares = new Middleware(new NullLogger());
        $this->em->getConnection()->getConfiguration()->setMiddlewares([$middlewares]);
    }

    public function truncate(string $entityFqcn): void
    {
        $this->em
            ->createQueryBuilder()
            ->delete($entityFqcn, 'e')
            ->getQuery()
            ->execute();
    }
}
