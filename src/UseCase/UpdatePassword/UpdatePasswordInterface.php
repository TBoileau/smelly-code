<?php

declare(strict_types=1);

namespace App\UseCase\UpdatePassword;

use App\Entity\User;

interface UpdatePasswordInterface
{
    public function __invoke(User $user): void;
}
