<?php

declare(strict_types=1);

namespace App\UseCase\Vote;

use App\Entity\SmellyCode;

interface DownVoteInterface
{
    public function __invoke(SmellyCode $smellyCode): void;
}
