<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TopUsersTest extends WebTestCase
{
    public function testIfTopUsersIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/top-users');

        $this->assertResponseIsSuccessful();
    }
}
