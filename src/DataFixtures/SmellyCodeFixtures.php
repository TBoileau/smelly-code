<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Carbon;
use App\Entity\Gist;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class SmellyCodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Tag> $tags */
        $tags = $manager->getRepository(Tag::class)->findAll();

        /** @var array<array-key, User> $users */
        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            /** @var array<array-key, User> $otherUsers */
            $otherUsers = array_diff($users, [$user]);

            for ($index = 1; $index <= 5; ++$index) {
                $manager->persist($this->createGist($otherUsers, $user, $tags));
            }

            for ($index = 1; $index <= 5; ++$index) {
                $manager->persist($this->createCarbon($otherUsers, $user, $tags));
            }
        }

        $manager->flush();
    }

    /**
     * @return array<array-key, class-string>
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class, TagFixtures::class];
    }

    /**
     * @param array<array-key, User> $users
     * @param array<array-key, Tag>  $tags
     */
    private function createGist(array $users, User $user, array $tags): Gist
    {
        shuffle($tags);

        $smellyCode = new Gist();
        $smellyCode->setUser($user);
        $smellyCode->setName('Smelly code');
        $smellyCode->setUrl('https://gist.github.com/TBoileau/46e591a7e668757777db6c52e9f6d8c5');
        $smellyCode->setTags(new ArrayCollection(array_slice($tags, 0, 3)));

        [$upVotes, $downVotes] = array_chunk($users, 5);

        array_walk($upVotes, static fn (User $user) => $smellyCode->getUpVotes()->add($user));
        array_walk($downVotes, static fn (User $user) => $smellyCode->getDownVotes()->add($user));

        return $smellyCode;
    }

    /**
     * @param array<array-key, User> $users
     * @param array<array-key, Tag>  $tags
     */
    private function createCarbon(array $users, User $user, array $tags): Carbon
    {
        shuffle($tags);

        $smellyCode = new Carbon();
        $smellyCode->setUser($user);
        $smellyCode->setName('Smelly code');
        $smellyCode->setUrl('https://carbon.now.sh/jimM1JPlkCNu64pcyD2N');
        $smellyCode->setTags(new ArrayCollection(array_slice($tags, 0, 3)));

        [$upVotes, $downVotes] = array_chunk($users, 5);

        array_walk($upVotes, static fn (User $user) => $smellyCode->getUpVotes()->add($user));
        array_walk($downVotes, static fn (User $user) => $smellyCode->getDownVotes()->add($user));

        return $smellyCode;
    }
}
