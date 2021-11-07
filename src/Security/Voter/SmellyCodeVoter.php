<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\SmellyCode;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class SmellyCodeVoter extends Voter
{
    public const VOTE = 'vote';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof SmellyCode && self::VOTE === $attribute;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if (!$token->getUser() instanceof UserInterface) {
            return false;
        }

        /** @var User $user */
        $user = $token->getUser();

        /** @var SmellyCode $smellyCode */
        $smellyCode = $subject;

        return $smellyCode->canVote($user);
    }
}
