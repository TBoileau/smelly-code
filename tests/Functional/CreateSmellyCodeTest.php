<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\SmellyCode;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreateSmellyCodeTest extends WebTestCase
{
    public function testIfAccessDeniedWhenITryToCreateGistWithoutBeLogged(): void
    {
        $client = static::createClient();

        $client->request('GET', '/new');

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('security_login');
    }

    public function testIfGistIsCreated(): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        $client->loginUser($user);

        $client->request('GET', '/new');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Créer', [
            'gist[url]' => 'https://gist.github.com/TBoileau/46e591a7e668757777db6c52e9f6d8c5',
            'gist[tags]' => 'Tag 1,foo',
        ]);

        $this->assertResponseStatusCodeSame(302);

        $this->assertEquals(101, $entityManager->getRepository(SmellyCode::class)->count([]));

        $this->assertEquals(11, $entityManager->getRepository(Tag::class)->count([]));
    }

    /**
     * @dataProvider provideBadUrl
     */
    public function testIfGistCreationFailed(string $url): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->findOneBy([]);

        $client->loginUser($user);

        $client->request('GET', '/new');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Créer', [
            'gist[url]' => $url,
            'gist[tags]' => 'Tag 1,foo',
        ]);

        $this->assertResponseStatusCodeSame(422);
    }

    public function provideBadUrl(): iterable
    {
        yield 'url is empty' => [''];
        yield 'url is invalid' => ['fail'];
        yield 'url is not a gist url' => ['https://www.google.com'];
    }
}
