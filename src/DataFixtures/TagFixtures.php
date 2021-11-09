<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($index = 1; $index <= 10; ++$index) {
            $tag = new Tag();
            $tag->setName(sprintf('Tag %d', $index));
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
