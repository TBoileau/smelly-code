<?php

declare(strict_types=1);

namespace App\UseCase\Register;

use App\Entity\User;

interface RegisterInterface
{
    public function __invoke(User $user): void;
}
