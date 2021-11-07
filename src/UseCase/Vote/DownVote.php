<?php

declare(strict_types=1);

namespace App\UseCase\Vote;

use App\Entity\SmellyCode;
use App\Entity\User;

final class DownVote extends Vote implements DownVoteInterface
{
    protected function vote(SmellyCode $smellyCode, User $user): void
    {
        $smellyCode->getDownVotes()->add($user);
    }
}
