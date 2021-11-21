<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TopSmellyCodesTest extends WebTestCase
{
    public function testIfTopSmellyCodesIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/top-smelly-codes');

        $this->assertResponseIsSuccessful();
    }
}
