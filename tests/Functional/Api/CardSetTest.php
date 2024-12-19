<?php

namespace App\Tests\Functional\Api;

use App\Entity\Card;
use App\Tests\Factory\CardFactory;
use App\Tests\Factory\CardSetFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CardSetTest extends BaseApiTestCase
{
    public const string BASE_URL = '/api/sets';

    #[Test]
    public function get_collection(): void
    {
        CardSetFactory::createMany(5);
        $this->client->request(Request::METHOD_GET, self::BASE_URL);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Set/get_collection');
    }

    #[Test]
    public function get_item(): void
    {
        /** @var Card $card */
        $card = CardFactory::createOne()->_save()->_real();
        $set = $card->getSet();

        $endpoint = \sprintf('%s/%s', self::BASE_URL, $set->getIdString());
        $this->client->request(Request::METHOD_GET, $endpoint);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Set/get_item');
    }

    #[Test]
    public function post(): void
    {
        $this->client->request(Request::METHOD_POST, self::BASE_URL, content: \json_encode([
            'name' => CardFactory::faker()->unique()->text(255),
        ]) ?: null);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Set/get_item', 201);
    }

    #[Test]
    public function patch(): void
    {
        /** @var Card $card */
        $card = CardFactory::createOne()->_save()->_real();
        $set = $card->getSet();

        $this->client->request(
            Request::METHOD_PATCH,
            \sprintf('%s/%s', self::BASE_URL, $set->getIdString()),
            server: ['HTTP_CONTENT_TYPE' => 'application/merge-patch+json'],
            content: \json_encode([
                'name' => CardFactory::faker()->unique()->text(255),
            ]) ?: null
        );

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Set/get_item');
    }

    #[Test]
    public function delete(): void
    {
        /** @var Card $card */
        $card = CardFactory::createOne()->_save()->_real();
        $set = $card->getSet();

        $endpoint = \sprintf('%s/%s', self::BASE_URL, $set->getIdString());
        $this->client->request(Request::METHOD_DELETE, $endpoint);

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT, $response);
    }
}
