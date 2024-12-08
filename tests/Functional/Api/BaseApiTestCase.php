<?php

namespace App\Tests\Functional\Api;

use ApiTestCase\JsonApiTestCase;
use PHPUnit\Framework\Attributes\Before;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class BaseApiTestCase extends JsonApiTestCase
{
    use ResetDatabase;
    use Factories;

    #[Before]
    public function setUpClient(): void
    {
        $this->client = static::createClient([], [
            'CONTENT_TYPE' => 'application/ld+json',
        ]);
    }
}
