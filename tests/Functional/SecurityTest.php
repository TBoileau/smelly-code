<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SecurityTest extends WebTestCase
{
    public function testIfUserIsRegistered(): void
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $client->submitForm('Sign up', [
            'registration[email]' => 'user+11@email.com',
            'registration[plainPassword]' => 'password',
            'registration[nickname]' => 'user+11',
        ]);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('security_login');

        /** @var UserRepository $userRepository */
        $userRepository = $client->getContainer()->get(UserRepository::class);

        $this->assertNotNull($userRepository->findOneBy(['email' => 'user+11@email.com']));
    }

    /**
     * @param array{email: string, password: string, nickname: string} $formData
     *
     * @dataProvider provideFailedRegisterData
     */
    public function testIfRegisterFailed(array $formData): void
    {
        $client = static::createClient();

        $client->request('GET', '/register');

        $client->submitForm('Sign up', $formData);

        $this->assertResponseStatusCodeSame(422);
    }

    public function provideFailedRegisterData(): iterable
    {
        $baseData = static fn (array $data) => $data + [
                'registration[email]' => 'user+11@email.com',
                'registration[plainPassword]' => 'password',
                'registration[nickname]' => 'user+11',
            ];

        yield 'email is empty' => [$baseData(['registration[email]' => ''])];
        yield 'password is empty' => [$baseData(['registration[plainPassword]' => ''])];
        yield 'nickname is empty' => [$baseData(['registration[nickname]' => ''])];
        yield 'email is invalid' => [$baseData(['registration[email]' => 'fail'])];
        yield 'email is not unique' => [$baseData(['registration[email]' => 'user+1@email.com'])];
        yield 'nickname is not unique' => [$baseData(['registration[nickname]' => 'user+1'])];
    }

    public function testIfLoginIsSuccessful(): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Sign in', [
            'email' => 'user+1@email.com',
            'password' => 'password',
        ]);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('smelly_code_show');
    }

    /**
     * @param array{email: string, password: string} $formData
     *
     * @dataProvider provideFailedLoginData
     */
    public function testIfLoginFailed(array $formData): void
    {
        $client = static::createClient();

        $client->request('GET', '/login');

        $client->submitForm('Sign in', $formData);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('security_login');
    }

    public function provideFailedLoginData(): iterable
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
