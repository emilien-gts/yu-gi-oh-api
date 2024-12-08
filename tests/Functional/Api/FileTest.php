<?php

namespace App\Tests\Functional\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Card;
use App\Tests\Factory\CardFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class FileTest extends ApiTestCase
{
    use ResetDatabase;
    use Factories;

    #[Test]
    public function add_file(): void
    {
        self::bootKernel();
        $client = self::createClient();

        /** @var Card $card */
        $card = CardFactory::createOne()->_save()->_real();

        $absolutePath = \sprintf('%s/tests/Resources/MAGO-EN025_PGR.png', self::$kernel->getProjectDir());
        $file = new UploadedFile($absolutePath, 'MAGO-EN025_PGR.png', 'image/png');

        $endpoint = \sprintf('%s/%s/image', CardTest::BASE_URL, $card->getIdString());

        $client->request('POST', $endpoint, [
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'files' => [
                    'file' => $file,
                ],
            ],
        ]);

        $this->assertResponseIsSuccessful();
    }
}
