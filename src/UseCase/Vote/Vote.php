<?php

declare(strict_types=1);

namespace App\UseCase\Vote;

use App\Entity\SmellyCode;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

abstract class Vote
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(SmellyCode $smellyCode): void
    {
        /** @var TokenInterface $token */
        $token = $this->tokenStorage->getToken();

        /** @var User $user */
        $user = $token->getUser();
        $this->vote($smellyCode, $user);
        $smellyCode->setUpdatedAt(new DateTimeImmutable());
        $this->entityManager->flush();

        /** @var array<array-key, SmellyCode> $smellyCodes */
        $smellyCodes = $this->requestStack->getSession()->get('smelly_codes', []);
        $smellyCodes[] = $smellyCode;
        $this->requestStack->getSession()->set('smelly_codes', $smellyCodes);
    }

    abstract protected function vote(SmellyCode $smellyCode, User $user): void;
}
