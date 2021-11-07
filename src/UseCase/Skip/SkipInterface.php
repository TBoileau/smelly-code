<?php

declare(strict_types=1);

namespace App\UseCase\Skip;

use App\Entity\SmellyCode;

interface SkipInterface
{
    public function __invoke(SmellyCode $smellyCode): void;
}
