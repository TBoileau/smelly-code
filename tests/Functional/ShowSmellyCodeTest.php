<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Entity\Gist;
use App\Entity\SmellyCode;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ShowSmellyCodeTest extends WebTestCase
{
    public function testIfSmellyCodeShown(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }

    /**
     * @dataProvider provideLinkTitles
     */
    public function testIfVoteIsSuccessful(string $linkTitle): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $gistAuthor */
        $gistAuthor = $entityManager->getRepository(User::class)->find(2);

        $smellyCode = new Gist();
        $smellyCode->setUser($gistAuthor);
        $smellyCode->setUrl('https://gist.github.com/TBoileau/46e591a7e668757777db6c52e9f6d8c5');

        $entityManager->persist($smellyCode);
        $entityManager->flush();

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find(1);

        $client->loginUser($user);

        $client->request('GET', sprintf('/%d', $smellyCode->getId()));

        $this->assertResponseIsSuccessful();

        $client->clickLink($linkTitle);

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('smelly_code_show');
    }

    public function provideLinkTitles(): iterable
    {
        yield 'up vote' => ['Up vote'];
        yield 'down vote' => ['Down vote'];
    }

    /**
     * @dataProvider providePaths
     */
    public function testIfAccessDeniedWhenITryToVoteAndIAmLogged(string $path): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var SmellyCode $smellyCode */
        $smellyCode = $entityManager->getRepository(SmellyCode::class)->findOneBy([]);

        /** @var User $user */
        $user = $entityManager->getRepository(User::class)->find(1);

        $client->loginUser($user);

        $client->request('GET', sprintf('/%d/%s', $smellyCode->getId(), $path));

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * @dataProvider providePaths
     */
    public function testIfAccessDeniedWhenITryToVoteAndIAmNotLogged(string $path): void
    {
        $client = static::createClient();

        /** @var EntityManagerInterface $entityManager */
        $entityManager = $client->getContainer()->get('doctrine.orm.entity_manager');

        /** @var User $gistAuthor */
        $gistAuthor = $entityManager->getRepository(User::class)->find(2);

        $smellyCode = new Gist();
        $smellyCode->setUser($gistAuthor);
        $smellyCode->setUrl('https://gist.github.com/TBoileau/46e591a7e668757777db6c52e9f6d8c5');

        $entityManager->persist($smellyCode);
        $entityManager->flush();

        $client->request('GET', sprintf('/%d/%s', $smellyCode->getId(), $path));

        $this->assertResponseStatusCodeSame(302);

        $client->followRedirect();

        $this->assertRouteSame('security_login');
    }

    public function providePaths(): iterable
    {
        yield 'up vote' => ['up-vote'];
        yield 'down vote' => ['down-vote'];
    }
}
