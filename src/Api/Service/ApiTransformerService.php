<?php

namespace App\Api\Service;

use AutoMapper\AutoMapperInterface;

class ApiTransformerService
{
    public function __construct(private readonly AutoMapperInterface $autoMapper)
    {
    }

    public function transform(object $source, string $targetFqcn): object
    {
        return $this->autoMapper->map($source, $targetFqcn);
    }
}
