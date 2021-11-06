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

    /**
     * @param array{email: string, password: string} $formData
     *
     * @dataProvider provideFailedData
     */
    public function testIfLoginFailed(array $formData): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Se connecter', $formData);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('security_login');
    }

    public function provideFailedData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
            'email' => 'user+1@email.com',
            'password' => 'password',
        ];

        yield 'email is empty' => [$baseData(['email' => ''])];
        yield 'password is empty' => [$baseData(['password' => ''])];
        yield 'email does not exist' => [$baseData(['email' => 'fail@email.com'])];
        yield 'password is incorrect' => [$baseData(['password' => 'fail'])];
        yield 'csrf invalid' => [$baseData(['_csrf_token' => 'fail'])];
    }
}
