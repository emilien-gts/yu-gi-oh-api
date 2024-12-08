<?php

namespace App\Api\State\Provider;

use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ApiResource\Error;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;

#[AsAlias('api_platform.state.error_provider')]
#[AsTaggedItem('api_platform.state.error_provider')]
final class ErrorProvider implements ProviderInterface
{
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $request = $context['request'];
        $exception = $request->attributes->get('exception');

        /** @var HttpOperation $operation */
        $status = $operation->getStatus() ?? 500;
        $error = Error::createFromException($exception, $status);

        if (404 === $status) {
            $error->setDetail('Resource not found');
        }

        return $error;
    }
}
