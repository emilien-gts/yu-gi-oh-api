<?php

namespace App\Api\Transformer\Property;

use App\Api\Resource\CardSetApi;
use App\Entity\CardSet;
use AutoMapper\Transformer\PropertyTransformer\PropertyTransformerInterface;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Uid\Uuid;

#[AutoconfigureTag('automapper.property_transformer')]
class EntityLinkerTransformer implements PropertyTransformerInterface
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function transform(mixed $value, object|array $source, array $context): mixed
    {
        if (!\is_object($value) || !\property_exists($value, 'id')) {
            return null;
        }

        $id = $value->id;
        if (!$id instanceof Uuid) {
            return null;
        }

        $existing = $this->em->find($this->getTargetFqcn($value), $id);
        if (null === $existing) {
            throw new NotFoundHttpException('Resource not found');
        }

        return $existing;
    }

    /**
     * @return class-string<object>
     */
    private function getTargetFqcn(object $value): string
    {
        return match (ClassUtils::getClass($value)) {
            CardSetApi::class => CardSet::class,
            default => throw new \InvalidArgumentException('Invalid value'),
        };
    }
}
