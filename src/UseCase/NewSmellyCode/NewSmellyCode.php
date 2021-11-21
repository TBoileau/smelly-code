<?php

declare(strict_types=1);

namespace App\UseCase\NewSmellyCode;

use App\Dto\SmellyCode as SmellyCodeDto;
use App\Entity\User;
use App\Factory\FlashMessageFactoryInterface;
use App\Factory\SmellyCodeFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class NewSmellyCode implements NewSmellyCodeInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TokenStorageInterface $tokenStorage,
        private SmellyCodeFactoryInterface $smellyCodeFactory,
        private FlashMessageFactoryInterface $flashMessageFactory
    ) {
    }

    public function __invoke(SmellyCodeDto $smellyCodeDto): void
    {
        $smellyCode = $this->smellyCodeFactory->createFromDto($smellyCodeDto);

        if (null === $this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser() instanceof User) {
            throw new AccessDeniedException(); // @codeCoverageIgnore
        }

        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $smellyCode->setUser($user);

        $this->entityManager->persist($smellyCode);
        $this->entityManager->flush();

        $this->flashMessageFactory->send(
            FlashMessageFactoryInterface::STATUS_SUCCESS,
            'Smelly code created.'
        );
    }
}
