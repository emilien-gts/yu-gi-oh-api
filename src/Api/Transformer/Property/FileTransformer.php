<?php

namespace App\Api\Transformer\Property;

use ApiPlatform\Metadata\UrlGeneratorInterface;
use AutoMapper\Transformer\PropertyTransformer\PropertyTransformerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Filesystem;

#[AutoconfigureTag('automapper.property_transformer')]
class FileTransformer implements PropertyTransformerInterface
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public/uploads/cards/')] private readonly string $publicDirectory,
        private readonly Filesystem $filesystem,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function transform(mixed $value, object|array $source, array $context): mixed
    {
        if (!\is_string($value)) {
            return null;
        }

        if (!$this->filesystem->exists($this->publicDirectory.$value)) {
            return null;
        }

        $url = $this->urlGenerator->generate('app_default_index', [], UrlGeneratorInterface::ABS_URL);

        return \sprintf('%s/uploads/cards/%s', $url, $value);
    }
}
