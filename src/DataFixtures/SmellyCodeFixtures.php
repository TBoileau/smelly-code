<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Gist;
use App\Entity\SmellyCode;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class SmellyCodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, User> $users */
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            /** @var array<array-key, User> $otherUsers */
            $otherUsers = array_diff($users, [$user]);

            for ($index = 1; $index <= 10; ++$index) {
                $manager->persist($this->createSmellyCode($otherUsers, $user));
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string>
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }

    /**
     * @param array<array-key, User> $users
     */
    private function createSmellyCode(array $users, User $user): SmellyCode
    {
        $smellyCode = new Gist();
        $smellyCode->setUser($user);
        $smellyCode->setUrl('https://gist.github.com/TBoileau/46e591a7e668757777db6c52e9f6d8c5');

        [$upVotes, $downVotes] = array_chunk($users, 5);

        array_walk($upVotes, static fn (User $user) => $smellyCode->getUpVotes()->add($user));
        array_walk($downVotes, static fn (User $user) => $smellyCode->getDownVotes()->add($user));

        return $smellyCode;
    }
}
