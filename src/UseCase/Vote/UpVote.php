<?php

declare(strict_types=1);

namespace App\UseCase\Vote;

use App\Entity\SmellyCode;
use App\Entity\User;

final class UpVote extends Vote implements UpVoteInterface
{
    protected function vote(SmellyCode $smellyCode, User $user): void
    {
        $smellyCode->getUpVotes()->add($user);
    }
}
