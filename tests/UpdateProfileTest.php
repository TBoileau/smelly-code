<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UpdateProfileTest extends WebTestCase
{
    public function testIfProfileIsUpdated(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        $client->loginUser($user);

        $client->request('GET', '/update-profile');

        $client->submitForm('Update', [
            'profile[nickname]' => 'Nickname',
            'profile[email]' => 'user+0@email.com',
            'profile[avatarFile]' => new UploadedFile(
                __DIR__.'/../public/uploads/avatar.png',
                'avatar_test.png',
                'image/png',
                null,
                true
            ),
        ]);

        $this->assertResponseStatusCodeSame(302);
    }

    /**
     * @param array{nickname: string, email: string, avatarFile: UploadedFile} $formData
     *
     * @dataProvider provideFailedData
     */
    public function testIfUpdateProfileIsFailed(array $formData): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        $client->loginUser($user);

        $client->request('GET', '/update-profile');

        $client->submitForm('Update', $formData);

        $this->assertResponseStatusCodeSame(422);
    }

    public function provideFailedData(): iterable
    {
        $baseData = static fn (array $data): array => $data + [
            'profile[nickname]' => 'Nickname',
            'profile[email]' => 'user+0@email.com',
            'profile[avatarFile]' => new UploadedFile(
                __DIR__.'/../public/uploads/avatar.png',
                'avatar.png',
                'image/png',
                null,
                true
            ),
        ];

        yield 'email is empty' => [$baseData(['profile[email]' => ''])];
        yield 'email is invalid' => [$baseData(['profile[email]' => 'fail'])];
        yield 'email is not unique' => [$baseData(['profile[email]' => 'user+2@email.com'])];
        yield 'nickname is empty' => [$baseData(['profile[nickname]' => ''])];
        yield 'nickname is not unique' => [$baseData(['profile[nickname]' => 'user+2'])];
        yield 'avatar is not an image' => [$baseData(['profile[avatarFile]' => new UploadedFile(
            __DIR__.'/../public/uploads/fail.txt',
            'fail.txt',
            'text/plain',
            null,
            true
        )])];
        yield 'avatar is too big' => [$baseData(['profile[avatarFile]' => new UploadedFile(
            __DIR__.'/../public/uploads/fail.jpg',
            'fail.jpg',
            'text/plain',
            null,
            true
        )])];
    }
}
