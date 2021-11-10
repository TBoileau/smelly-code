<?php

declare(strict_types=1);

namespace App\UseCase\NewSmellyCode;

use App\Dto\SmellyCode;

interface NewSmellyCodeInterface
{
    public function __invoke(SmellyCode $smellyCode): void;
}
