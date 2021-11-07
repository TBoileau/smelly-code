<?php

declare(strict_types=1);

namespace App\UseCase\Register;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class Register implements RegisterInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function __invoke(User $user): void
    {
        /** @var string $plainPassword */
        $plainPassword = $user->getPlainPassword();
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $plainPassword));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
