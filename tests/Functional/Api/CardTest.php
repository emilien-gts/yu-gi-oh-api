<?php

namespace App\Tests\Functional\Api;

use App\Entity\Card;
use App\Tests\Factory\CardFactory;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Request;

class CardTest extends BaseApiTestCase
{
    private const string BASE_URL = '/api/cards';

    #[Test]
    public function get_collection(): void
    {
        CardFactory::createMany(5);
        $this->client->request(Request::METHOD_GET, self::BASE_URL);

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_collection');
    }

    #[Test]
    public function get_item(): void
    {
        $item = CardFactory::createOne();
        $item->_assertPersisted();

        /** @var Card $card */
        $card = $item->_real();

        $this->client->request(Request::METHOD_GET, \sprintf('%s/%s', self::BASE_URL, $card->getIdString()));

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'Card/get_item');
    }
}
