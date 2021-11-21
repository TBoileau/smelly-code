<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UpdatePasswordTest extends WebTestCase
{
    public function testIfPasswordIsUpdated(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        $client->loginUser($user);

        $client->request('GET', '/update-password');

        $client->submitForm('Update', [
            'user[currentPassword]' => 'password',
            'user[plainPassword]' => 'edit_password',
        ]);

        $this->assertResponseStatusCodeSame(302);
    }

    /**
     * @param array{password: string} $formData
     *
     * @dataProvider provideFailedData
     */
    public function testIfUpdatePasswordIsFailed(array $formData): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        $client->loginUser($user);

        $client->request('GET', '/update-password');

        $client->submitForm('Update', $formData);

        $this->assertResponseStatusCodeSame(422);
    }

    public function provideFailedData(): iterable
    {
        $baseData = static fn (array $data): array => $data + [
                'user[currentPassword]' => 'password',
                'user[plainPassword]' => 'edit_password',
        ];

        yield 'plainPassword is empty' => [$baseData(['user[plainPassword]' => ''])];
        yield 'currentPassword is empty' => [$baseData(['user[currentPassword]' => ''])];
        yield 'currentPassword is wrong' => [$baseData(['user[currentPassword]' => 'wrong'])];
    }
}
