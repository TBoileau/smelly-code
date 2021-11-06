<?php

declare(strict_types=1);

namespace Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SecurityTest extends WebTestCase
{
    public function testIfLoginIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Se connecter', [
            'email' => 'user+1@email.com',
            'password' => 'password',
        ]);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('home');
    }
}
