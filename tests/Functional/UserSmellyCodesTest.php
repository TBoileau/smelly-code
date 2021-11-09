<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class UserSmellyCodesTest extends WebTestCase
{
    public function testIfUserSmellyCodesIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/user/user+1');

        $this->assertResponseIsSuccessful();
    }
}
