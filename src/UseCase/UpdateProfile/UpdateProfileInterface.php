<?php

declare(strict_types=1);

namespace App\UseCase\UpdateProfile;

use App\Entity\User;

interface UpdateProfileInterface
{
    public function __invoke(User $user): void;
}
