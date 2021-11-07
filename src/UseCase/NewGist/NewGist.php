<?php

declare(strict_types=1);

namespace App\UseCase\NewGist;

use App\Entity\Gist;
use Doctrine\ORM\EntityManagerInterface;

final class NewGist implements NewGistInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(Gist $gist): void
    {
        $this->entityManager->persist($gist);
        $this->entityManager->flush();
    }
}
