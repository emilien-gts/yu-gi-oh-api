<?php

namespace App\Tests\Functional\Api;

use App\Entity\Card;
use App\Entity\CardSet;
use App\Enum\Card\CardAttribute;
use App\Enum\Card\CardRarity;
use App\Enum\Card\CardType;
use App\Tests\Factory\CardFactory;
use App\Tests\Factory\CardSetFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CardTest extends BaseApiTestCase
{
    public const string BASE_URL = '/api/cards';

    #[Test]
    public function get_collection(): void
    {
        CardFactory::createMany(5);
        $this->client->request(Request::METHOD_GET, self::BASE_URL);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_collection');
    }

    #[Test]
    public function get_collection_with_filters(): void
    {
        /** @var Card $card */
        $card = CardFactory::createOne(['name' => 'Dark Magician', 'password' => '46986414'])->_save()->_real();
        CardFactory::createOne(['name' => 'Dark Magician Girl', 'password' => '38033121']);
        CardFactory::createMany(5);

        // filter by name (partial)
        $endpoint = \sprintf('%s?name=Magician', self::BASE_URL);
        $this->client->request(Request::METHOD_GET, $endpoint);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_collection');

        /** @var array{totalItems: int} $content */
        $content = \json_decode($response->getContent() ?: '', true);
        $this->assertSame(2, $content['totalItems']);

        // filter by password (exact)
        $endpoint = \sprintf('%s?password=%s', self::BASE_URL, '38033121');
        $this->client->request(Request::METHOD_GET, $endpoint);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_collection');

        /** @var array{totalItems: int} $content */
        $content = \json_decode($response->getContent() ?: '', true);
        $this->assertSame(1, $content['totalItems']);

        // filter by set (exact)
        $endpoint = \sprintf('%s?set=%s', self::BASE_URL, $card->getSet()->getIdString());
        $this->client->request(Request::METHOD_GET, $endpoint);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_collection');

        /** @var array{totalItems: int} $content */
        $content = \json_decode($response->getContent() ?: '', true);
        $this->assertSame(1, $content['totalItems']);
    }

    #[Test]
    public function get_item(): void
    {
        /** @var Card $card */
        $card = CardFactory::createOne()->_save()->_real();

        $endpoint = \sprintf('%s/%s', self::BASE_URL, $card->getIdString());
        $this->client->request(Request::METHOD_GET, $endpoint);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_item');
    }

    #[Test]
    public function post(): void
    {
        /** @var CardSet $set */
        $set = CardSetFactory::createOne()->_save()->_real();

        $this->client->request(Request::METHOD_POST, self::BASE_URL, content: \json_encode([
            'attack' => CardFactory::faker()->randomNumber(),
            'attribute' => CardFactory::faker()->randomElement(CardAttribute::cases()),
            'defense' => CardFactory::faker()->randomNumber(),
            'imageFilename' => CardFactory::faker()->text(255),
            'level' => CardFactory::faker()->numberBetween(1, 32767),
            'name' => CardFactory::faker()->unique()->text(255),
            'number' => CardFactory::faker()->text(255),
            'otherName' => CardFactory::faker()->text(255),
            'password' => '92223430',
            'rarity' => CardFactory::faker()->randomElement(CardRarity::cases()),
            'set' => \sprintf('/api/sets/%s', $set->getIdString()),
            'type' => CardFactory::faker()->randomElement(CardType::cases()),
            'types' => [
                CardType::ROCK->value,
                CardType::DINOSAUR->value,
            ],
        ]) ?: null);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_item', 201);
    }

    #[Test]
    public function patch(): void
    {
        /** @var Card $card */
        $card = CardFactory::createOne()->_save()->_real();
        $set = $card->getSet();

        $this->client->request(
            Request::METHOD_PATCH,
            \sprintf('%s/%s', self::BASE_URL, $card->getIdString()),
            server: ['HTTP_CONTENT_TYPE' => 'application/merge-patch+json'],
            content: \json_encode([
                'attack' => CardFactory::faker()->randomNumber(),
                'attribute' => CardFactory::faker()->randomElement(CardAttribute::cases()),
                'defense' => CardFactory::faker()->randomNumber(),
                'imageFilename' => CardFactory::faker()->text(255),
                'level' => CardFactory::faker()->numberBetween(1, 32767),
                'name' => CardFactory::faker()->unique()->text(255),
                'number' => CardFactory::faker()->text(255),
                'otherName' => CardFactory::faker()->text(255),
                'password' => '92223430',
                'rarity' => CardFactory::faker()->randomElement(CardRarity::cases()),
                'set' => \sprintf('/api/sets/%s', $set->getIdString()),
                'type' => CardFactory::faker()->randomElement(CardType::cases()),
                'types' => [
                    CardType::ROCK->value,
                    CardType::DINOSAUR->value,
                ],
            ]) ?: null
        );

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_item');
    }

    #[Test]
    public function delete(): void
    {
        /** @var Card $card */
        $card = CardFactory::createOne()->_save()->_real();

        $endpoint = \sprintf('%s/%s', self::BASE_URL, $card->getIdString());
        $this->client->request(Request::METHOD_DELETE, $endpoint);

        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT, $response);
    }
}
